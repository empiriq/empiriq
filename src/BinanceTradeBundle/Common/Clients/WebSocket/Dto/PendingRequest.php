<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\Dto;

use React\Promise\Deferred;

final readonly class PendingRequest
{
    public function __construct(
        public string $id,
        public Deferred $deferred,
        public array $request,
        public string $type,
    ) {
    }
}
