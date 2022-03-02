<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use Miklcct\ThinPhpApp\View\PhpTemplate;
use Psr\Http\Message\StreamFactoryInterface;

class JourneyView extends PhpTemplate {
    public function __construct(
        StreamFactoryInterface $streamFactory
        , protected readonly ?Journey $journey
        , protected readonly array $availableTickets
        , protected readonly ?string $defaultHost = null
        , protected readonly ?int $defaultPort = null
        , protected readonly ?string $defaultDatabase = null
    ) {
        parent::__construct($streamFactory);
    }

    /**
     * Get the file system path to the template.
     *
     * @return string
     */
    protected function getPathToTemplate() : string {
        return __DIR__ . '/../resource/journey.xhtml.php';
    }

    public function getContentType() : ?string {
        return 'application/xhtml+xml';
    }

}