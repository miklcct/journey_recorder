<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use JsonSerializable;

class Ticket implements JsonSerializable {
    public function __construct(
        public readonly ?int $serial
        , public readonly string $description
        , public readonly string $currencyCode
        , public readonly int $price
        , public readonly bool $advance = false
        , public readonly int $carnets = 1
        , public readonly ?int $carnetsUsed = null
        , public readonly bool $expired = false
        , public readonly ?float $coverFrom = null
        , public readonly ?float $coverTo = null
    ) {
    }

    public function jsonSerialize() : array {
        return (array)$this;
    }

    public static function fromArray(array $data) : static {
        return new static(
            $data['serial'] ?? null
            , $data['description']
            , $data['currencyCode']
            , $data['price']
            , $data['advance'] ?? false
            , $data['carnets']
            , $data['carnetsUsed'] ?? null
            , $data['expired']
            , $data['coverFrom'] ?? null
            , $data['coverTo'] ?? null
        );
    }
}