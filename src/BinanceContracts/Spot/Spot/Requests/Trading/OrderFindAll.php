<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Requests\Trading;

readonly class OrderFindAll
{
    public function __construct(
        public string $symbol,
        public ?int $startTime = null,
        public ?int $endTime = null,
        public ?int $limit = null,
    ) {
    }
}
