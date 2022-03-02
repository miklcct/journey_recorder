<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use Miklcct\ThinPhpApp\Response\ViewResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

class JourneyResponseFactory implements JourneyResponseFactoryInterface {
    public function __construct(
        ViewResponseFactoryInterface $viewResponseFactory
        , StreamFactoryInterface $streamFactory
        , private readonly ?string $defaultHost = null
        , private readonly ?int $defaultPort = null
        , private readonly ?string $defaultDatabase = null
    ) {

        $this->viewResponseFactory = $viewResponseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function __invoke(
        ServerRequestInterface $request
        , ?Journey $journey
        , array $availableTickets
    ) : ResponseInterface {
        return ($this->viewResponseFactory)(
            new JourneyView(
                $this->streamFactory
                , $journey
                , $availableTickets
                , $this->defaultHost
                , $this->defaultPort
                , $this->defaultDatabase
            )
        );
    }

    private ViewResponseFactoryInterface $viewResponseFactory;
    private StreamFactoryInterface $streamFactory;
}