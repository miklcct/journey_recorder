<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use DateTimeImmutable;
use DateTimeZone;
use Miklcct\JourneyRecorder\Journey as JourneyModel;
use Miklcct\ThinPhpApp\Controller\Application;
use mysqli;
use mysqli_result;
use mysqli_sql_exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Teapot\HttpException;
use Teapot\StatusCode\RFC\RFC4918;
use Teapot\StatusCode\RFC\RFC7231;
use Throwable;
use Yiisoft\Session\SessionInterface;
use Yiisoft\Session\SessionMiddleware;
use function array_fill;
use function array_filter;
use function array_keys;
use function array_map;
use function array_merge;
use function array_values;
use function assert;
use function count;
use function implode;
use function Miklcct\ThinPhpApp\Utility\nullable;
use function mysqli_report;
use function round;
use function str_repeat;
use function str_replace;
use function strtoupper;
use const MYSQLI_ASSOC;
use const MYSQLI_CLIENT_SSL;
use const MYSQLI_OPT_SSL_VERIFY_SERVER_CERT;
use const MYSQLI_REPORT_ERROR;
use const MYSQLI_REPORT_STRICT;

class JourneyApplication extends Application {
    public function __construct(
        SessionMiddleware $sessionMiddleware
        , ResponseFactoryInterface $responseFactory
        , JourneyResponseFactoryInterface $journeyResponseFactory
        , SessionInterface $session
    ) {
        $this->sessionMiddleware = $sessionMiddleware;
        $this->responseFactory = $responseFactory;
        $this->journeyResponseFactory = $journeyResponseFactory;
        $this->session = $session;
    }

    /**
     * Process the request after passed through the middlewares
     *
     * This the main controller.
     * The request processed by the middlewares before it reaches this method,
     * and the response is processed by the middlewares before it is send out.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Throwable
     */
    protected function run(ServerRequestInterface $request)
    : ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $this->processPostRequest($request);
            return $this->responseFactory->createResponse(RFC7231::SEE_OTHER)
                ->withHeader('Location', $request->getUri()->__toString());
        }
        return ($this->journeyResponseFactory)(
            $request
            , $this->session->get('journey')
            , $this->session->get('availableTickets', [])
        );
    }

    protected function getMiddlewares() : array {
        return array_merge(
            parent::getMiddlewares()
            , [$this->sessionMiddleware]
        );
    }

    private function createDatabaseConnection(ServerRequestInterface $request)
    : mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $body = (array)$request->getParsedBody();
        $connection = new Mysqli();
        $connection->real_connect(
            $body['host']
            , $body['user']
            , $body['password']
            , $body['database']
            , (int)$body['port']
            , ''
            , MYSQLI_OPT_SSL_VERIFY_SERVER_CERT | MYSQLI_CLIENT_SSL
        );
        return $connection;
    }

    private function getJourneyFromRequest(ServerRequestInterface $request)
    : JourneyModel {
        $body = (array)$request->getParsedBody();
        $array = array_merge(
            ...array_map(
                static fn($key, $value) : array => [
                    str_replace('_', ' ', (string)$key)
                    => $value === '' ? null : $value,
                ]
                , array_keys($body)
                , array_values($body)
            )
        );
        return JourneyModel::fromArray($array);
    }

    private function getStringFromTimestamp(DateTimeImmutable $timestamp)
    : string
    {
        return $timestamp->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i' /* no seconds */);
    }

    private function processPostRequest(ServerRequestInterface $request) : void {
        $connection = $this->createDatabaseConnection($request);
        $connection->query(
        /** @lang MySQL */
            "set time_zone = '+0:00'"
        );
        $connection->begin_transaction();
        try {
            if (isset(((array)$request->getParsedBody())['last'])) {
                $journey = null;
                $id = null;
                $switch = 1;
            } else {
                $journey = $this->getJourneyFromRequest($request);
                $id = $this->insertJourney($connection, $journey);
                $tickets = $request->getParsedBody()['ticket_uses'] ?? [];
                foreach ($tickets as &$ticket) {
                    $ticket['serial'] ??= '';
                    $ticket['description'] ??= '';
                    if ($ticket['serial'] === '' && $ticket['description'] !== '') {
                        try {
                            $insert_ticket_statement = query_database(
                                $connection
                                , 'insert into tickets (description, currency, price, carnets) values (?, ?, ?, ?)'
                                , 'ssii'
                                , $ticket['description']
                                , strtoupper($ticket['currency'])
                                , (int)round($ticket['price'] * 10 ** get_currency_digits(strtoupper($ticket['currency'])))
                                , $ticket['carnets']
                            );
                        } catch (mysqli_sql_exception $exception) {
                            throw new HttpException(
                                'Cannot insert ticket into database: '
                                . $exception->getMessage()
                                , RFC4918::UNPROCESSABLE_ENTITY
                                , $exception
                            );
                        }
                        $ticket['serial'] = $insert_ticket_statement->insert_id;
                    }
                }
                unset($ticket);
                $tickets = array_filter($tickets, static fn(array $item) => $item['serial'] !== '');
                foreach ($tickets as $ticket) {
                    if ($ticket['expire'] ?? null) {
                        query_database($connection, 'update tickets set expired = true where serial = ?', 'i', $ticket['serial']);
                    }
                }
                $parameters = implode(
                    ' union select '
                    , array_fill(
                        0
                        , count($tickets)
                        , '? as `input serial`, ? as `new carnet`, ? as `cover from`, ? as `cover to`'
                    )
                );
                if ($tickets !== []) {
                    try {
                        query_database(
                            $connection,
                            /** @lang MariaDB */ <<< EOF
# noinspection SqlResolve
insert into `ticket uses` (`journey serial`, `ticket serial`, `carnet sequence`, `cover from`, `cover to`)
    select ?, `input serial`, ifnull(max(`carnet sequence`), -1) + `new carnet`, input.`cover from`, input.`cover to`
    from `ticket uses`
        right join (select $parameters) as `input`
        on `ticket serial` = `input serial`
    group by `input serial`
EOF
                            , 'i' . str_repeat('iidd', count($tickets))
                            , ...array_merge(
                                [$id]
                                , ...array_map(
                                    static fn(array $ticket) : array => [
                                        $ticket['serial']
                                        , (bool)($ticket['new carnet'] ?? 0)
                                        , nullif($ticket['cover from'] ?? '', '')
                                        , nullif($ticket['cover to'] ?? '', '')
                                    ]
                                    , $tickets
                                )
                            )
                        );
                    } catch (mysqli_sql_exception $exception) {
                        throw new HttpException(
                            'Cannot insert ticket usages into database: '
                            . $exception->getMessage()
                            , RFC4918::UNPROCESSABLE_ENTITY
                            , $exception
                        );
                    }
                }
                $switch = 0;
            }
            $this->session->set('availableTickets', $this->getAvailableTickets($connection));
            $query_statement = query_database(
                $connection
                , <<< 'EOF'
select serial
    , type
    , network
    , route
    , destination
    , `boarding place`
    , `alighting place`
    , `cabin number`
    , `boarding time stamp` + interval `boarding time offset minutes` minute
        as `boarding time`
    , cast(`boarding time offset minutes` as decimal) / 60
        as `boarding time offset`
    , `alighting time stamp` + interval `alighting time offset minutes` minute
        as `alighting time`
    , cast(`alighting time offset minutes` as decimal) / 60
        as `alighting time offset`
    , `time taken`
    , distance
    , speed
    from journeys
    where serial = ? or ?
    order by serial desc
    limit 1
EOF
                , 'ii'
                , $id
                , $switch
            );
            $result = $query_statement->get_result();
            assert($result instanceof mysqli_result);
            $row = $result->fetch_assoc();
            if ($row === null) {
                if ($journey !== null) {
                    throw new RuntimeException(
                        'Cannot retrieve back the inserted row.'
                    );
                }
                return;
            }
            assert($row !== false);
            $ticket_statement = query_database(
                $connection
                , <<< 'EOF'
select 
    tickets.serial
    , tickets.description
    , tickets.currency
    , tickets.price
    , tickets.carnets
    , `ticket uses`.`carnet sequence`
    , tickets.expired
    , `ticket uses`.`cover from`
    , `ticket uses`.`cover to`
    from tickets inner join `ticket uses` on tickets.serial = `ticket uses`.`ticket serial`
    where `journey serial` = ?
EOF
                , 'i'
                , $row['serial']
            );
            $ticket_result = $ticket_statement->get_result();
            assert($ticket_result instanceof mysqli_result);
            $row['tickets'] = array_map(
                static fn(array $ticket_row) : array
                => (new Ticket(
                    serial: $ticket_row['serial']
                    , description: $ticket_row['description']
                    , currencyCode: $ticket_row['currency']
                    , price: $ticket_row['price']
                    , carnets: $ticket_row['carnets']
                    , carnetsUsed: $ticket_row['carnet sequence']
                    , expired: (bool)$ticket_row['expired']
                    , coverFrom: nullable($ticket_row['cover from'], fn($x) => (float)$x)
                    , coverTo: nullable($ticket_row['cover to'], fn($x) => (float)$x)
                ))->jsonSerialize()
                , $ticket_result->fetch_all(MYSQLI_ASSOC)
            );
            $stored_journey = JourneyModel::fromArray($row);
            if ($journey !== null && !$stored_journey->equals($journey)) {
                throw new RuntimeException(
                    'The journey retrieved is not identical to the given.'
                );
            }
            $connection->commit();
            $this->session->set('journey', $stored_journey);
        } catch (Throwable $e) {
            $connection->rollback();
            throw $e;
        }
    }

    private function insertJourney(mysqli $connection, JourneyModel $journey) : int {
        if ($journey->distance === null) {
            $journey->distance = $this->getDistance($connection, $journey);
        }
        $boarding_time_string
            = $this->getStringFromTimestamp($journey->boardingTime);
        $alighting_time_string
            = $this->getStringFromTimestamp($journey->alightingTime);
        $boarding_time_offset_minutes
            = $journey->boardingTime->getOffset() / 60;
        $alighting_time_offset_minutes
            = $journey->alightingTime->getOffset() / 60;
        try {
            $statement = query_database(
                $connection
                , <<< 'EOF'
insert into journeys (
    type
    , network
    , route
    , destination
    , `boarding place`
    , `alighting place`
    , `cabin number`
    , `boarding time stamp`
    , `alighting time stamp`
    , `boarding time offset minutes`
    , `alighting time offset minutes`
    , `distance`
)
    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
EOF
                , 'sssssssssiid'
                , $journey->type
                , $journey->network
                , $journey->route
                , $journey->destination
                , $journey->boardingPlace
                , $journey->alightingPlace
                , $journey->cabinNumber
                , $boarding_time_string
                , $alighting_time_string
                , $boarding_time_offset_minutes
                , $alighting_time_offset_minutes
                , $journey->distance
            );
        } catch (mysqli_sql_exception $exception) {
            throw new HttpException(
                'Cannot insert journey to database, check your data correctness: '
                . $exception->getMessage()
                , RFC4918::UNPROCESSABLE_ENTITY
                , $exception
            );
        }
        return $statement->insert_id;
    }

    private function getAvailableTickets(mysqli $connection) : array {
        $statement = query_database(
            $connection
            , <<< 'EOF'
select serial, description, currency, price, carnets, `carnets used`, expired
    from `tickets view`
    where not expired
    order by serial
EOF
        );
        $result = $statement->get_result();
        assert($result instanceof mysqli_result);
        $return_value = [];
        while (($row = $result->fetch_assoc()) !== null) {
            assert($row !== false);
            $return_value[] = new Ticket(
                $row['serial']
                , $row['description']
                , $row['currency']
                , $row['price']
                , $row['carnets']
                , $row['carnets used']
                , (bool)$row['expired']
            );
        }
        return $return_value;
    }

    private function getDistance(
        mysqli $connection,
        JourneyModel $journey
    ) : ?float {
        $query_statement = query_database(
            $connection
            , <<< 'EOF'
select distance
    from journeys
    where type = ?
        and network = ?
        and route = ?
        and destination = ?
        and `boarding place` = ?
        and `alighting place` = ?
    order by `boarding time stamp` desc
    limit 1
EOF
            , 'ssssss'
            , $journey->type
            , $journey->network
            , $journey->route
            , $journey->destination
            , $journey->boardingPlace
            , $journey->alightingPlace
        );
        $result = $query_statement->get_result();
        assert($result instanceof mysqli_result);
        $value = $result->fetch_column();
        return in_array($value, [false, null], true) ? null : (float)$value;
    }

    private ResponseFactoryInterface $responseFactory;
    private SessionMiddleware $sessionMiddleware;
    private JourneyResponseFactoryInterface $journeyResponseFactory;
    private SessionInterface $session;
}