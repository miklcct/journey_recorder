<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use Miklcct\ThinPhpApp\Response\ViewResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Yiisoft\Session\SessionInterface;

class JourneyResponseFactory implements JourneyResponseFactoryInterface {
    public function __construct(
        ViewResponseFactoryInterface $viewResponseFactory
        , StreamFactoryInterface $streamFactory
        , private readonly ?string $defaultHost = null
        , private readonly ?int $defaultPort = null
        , private readonly ?string $defaultDatabase = null
        , private readonly ?SessionInterface $session = null
        , private readonly ?string $defaultCurrency = null
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
                , $this->defaultHost ?? $this->session->get('host')
                , $this->defaultPort ?? $this->session->get('port', 3306)
                , $this->defaultDatabase ?? $this->session->get('database')
                , $this->defaultCurrency ?? $this->session->get('currency')
                , scope: preg_replace('/\?.*$/', '', $request->getServerParams()['REQUEST_URI'])
            )
        );
    }

    private ViewResponseFactoryInterface $viewResponseFactory;
    private StreamFactoryInterface $streamFactory;
}