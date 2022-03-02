<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use JsonSerializable;
use function abs;
use function array_map;
use function sprintf;
use function sscanf;
use function usort;
use const INF;

class Journey implements JsonSerializable {
    final public function __construct() {
    }

    public static function fromArray(array $data) : self {
        $journey = new static();
        $journey->serial = $data['serial'] ?? null;
        $journey->type = $data['type'];
        $journey->network = $data['network'];
        $journey->route = $data['route'];
        $journey->destination = $data['destination'];
        $journey->boardingPlace = $data['boarding place'];
        $journey->alightingPlace = $data['alighting place'];
        $journey->cabinNumber = $data['cabin number'];
        $journey->boardingTime
            = new DateTimeImmutable(
            $data['boarding time']
            , static::createDateTimeZoneFromOffsetInHours((float)$data['boarding time offset'])
        );
        $journey->alightingTime
            = new DateTimeImmutable(
            $data['alighting time']
            , static::createDateTimeZoneFromOffsetInHours((float)$data['alighting time offset'])
        );
        if (isset($data['time taken'])) {
            $negative = ($data['time taken'][0] ?? null) === '-';
            sscanf(
                $data['time taken']
                , '%d:%d'
                , $h
                , $m
            );
            if ($negative) {
                $h = -$h;
            }
            $journey->timeTaken
                = new DateInterval("PT{$h}H{$m}M");
            if ($negative) {
                $journey->timeTaken->invert = 1;
            }
        }
        $journey->distance = $data['distance'] !== null ? (float)$data['distance'] : null;
        $journey->speed = $data['speed'] ?? null;
        $journey->tickets = array_map(
            Ticket::fromArray(...)
            ,$data['tickets'] ?? []
        );
        usort(
            $journey->tickets
            , static function (Ticket $a, Ticket $b) : int {
            return ($a->coverFrom ?? -INF) <=> ($b->coverFrom ?? -INF);
        }
        );
        return $journey;
    }

    public function toArray() : array {
        return [
            'serial' => $this->serial,
            'type' => $this->type,
            'network' => $this->network,
            'route' => $this->route,
            'destination' => $this->destination,
            'boarding place' => $this->boardingPlace,
            'alighting place' => $this->alightingPlace,
            'cabin number' => $this->cabinNumber,
            'boarding time' => $this->boardingTime->format('Y-m-d H:i'),
            'boarding time offset' => $this->boardingTime->getOffset() / 3600,
            'alighting time' => $this->alightingTime->format('Y-m-d H:i'),
            'alighting time offset' => $this->alightingTime->getOffset() / 3600,
            'time taken' => $this->timeTaken->format('%r%H:%I'),
            'distance' => $this->distance,
            'speed' => $this->speed,
            'tickets' => array_map(static fn(Ticket $ticket) => $ticket->jsonSerialize(), $this->tickets),
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() : array {
        return $this->toArray();
    }

    public function equals(Journey $other) : bool {
        foreach (
            [
                [$this->type, $other->type],
                [$this->network, $other->network],
                [$this->route, $other->route],
                [$this->destination, $other->destination],
                [$this->boardingPlace, $other->boardingPlace],
                [$this->alightingPlace, $other->alightingPlace],
                [$this->cabinNumber, $other->cabinNumber],
                [
                    $this->boardingTime->getTimestamp(),
                    $other->boardingTime->getTimestamp(),
                ],
                [
                    $this->alightingTime->getTimestamp(),
                    $other->alightingTime->getTimestamp(),
                ],
                [
                    $this->boardingTime->getOffset(),
                    $other->boardingTime->getOffset(),
                ],
                [
                    $this->alightingTime->getOffset(),
                    $other->alightingTime->getOffset(),
                ],
            ]
            as $pair
        ) {
            if ($pair[0] !== $pair[1]) {
                return false;
            }
        }
        return abs($this->distance - $other->distance) < 0.001;
    }

    public ?int $serial;
    public string $type;
    public string $network;
    public ?string $route;
    public ?string $destination;
    public string $boardingPlace;
    public string $alightingPlace;
    public ?string $cabinNumber;
    public DateTimeImmutable $boardingTime;
    public DateTimeImmutable $alightingTime;
    public DateInterval $timeTaken;
    public ?float $distance;
    public ?float $speed;
    /** @var Ticket[] */
    public array $tickets;

    private static function createDateTimeZoneFromOffsetInHours(float $offset)
    : DateTimeZone
    {
        return new DateTimeZone(
            sprintf(
                '%s%02d%02d'
                , $offset >= 0 ? '+' : '-'
                , (int)abs($offset)
                , abs($offset) * 60 % 60
            )
        );
    }
}