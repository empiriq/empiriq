<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\Websocket\Dto;

use React\Promise\Deferred;

readonly class PendingRequest
{
    public function __construct(
        public string $id,
        public Deferred $deferred,
        public array $request,
        public string $type,
    ) {
    }
}
