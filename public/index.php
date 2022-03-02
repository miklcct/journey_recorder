<?php
declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Http\Factory\Guzzle\ResponseFactory;
use Http\Factory\Guzzle\StreamFactory;
use Miklcct\JourneyRecorder\JourneyApplication;
use Miklcct\JourneyRecorder\JourneyResponseFactory;
use Miklcct\ThinPhpApp\Exception\ExceptionErrorHandler;
use Miklcct\ThinPhpApp\Exception\ResponseFactoryExceptionHandler;
use Miklcct\ThinPhpApp\Response\ExceptionResponseFactoryInterface;
use Miklcct\ThinPhpApp\Response\ResponseSender;
use Miklcct\ThinPhpApp\Response\ViewResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Teapot\StatusCode\Http;
use Yiisoft\Session\Session;
use Yiisoft\Session\SessionMiddleware;
use function Http\Response\send;

require __DIR__ . '/../vendor/autoload.php';

function get_exception_details(Throwable $exception, bool $nested = false) : string {
    $previous = $exception->getPrevious();
    return sprintf(
            "%s: %s:%d:\n%s: %s\n\nStack trace:\n%s\n\n"
            , $nested ? 'Caused by' : 'Uncaught exception'
            , $exception->getFile()
            , $exception->getLine()
            , get_class($exception)
            , $exception->getMessage()
            , $exception->getTraceAsString()
        ) . ($previous !== null ? get_exception_details($previous, true) : '');
}

set_error_handler(new ExceptionErrorHandler());
set_exception_handler(
    new ResponseFactoryExceptionHandler(
        new class implements ExceptionResponseFactoryInterface {
            public function __invoke(Throwable $exception) : ResponseInterface {
                $status_code = $exception instanceof \Teapot\HttpException ? $exception->getCode()
                    : Http::INTERNAL_SERVER_ERROR;
                return new Response(
                    $status_code
                    , ['Content-Type' => 'text/plain; charset=utf-8']
                    , get_exception_details($exception)
                );
            }
        }
        , new ResponseSender()
    )
);

$session = new Session(
    [
        'save_path' => __DIR__ . '/../var/sessions',
        'gc_maxlifetime' => 30 * 24 * 60 * 60 /* 30 days */,
        'cookie_lifetime' => 7 * 24 * 60 * 60 /* 7 days */,
        'cookie_secure' => !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']),
    ]
);

$response_factory = new ResponseFactory();
send(
    (new JourneyApplication(
        new SessionMiddleware($session)
        , $response_factory
        , new JourneyResponseFactory(
            new ViewResponseFactory($response_factory)
            , new StreamFactory()
            , null
            , null
            , null
            , $session
        )
        , $session
    ))->handle(ServerRequest::fromGlobals())
);