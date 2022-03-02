<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface JourneyResponseFactoryInterface {
    /**
     * Generate a response showing the journey last inserted and the tickets
     * available
     *
     * @param ServerRequestInterface $request
     * @param Journey|null $journey
     * @param Ticket[] $availableTickets
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request
        , ?Journey $journey
        , array $availableTickets
    ) : ResponseInterface;
}