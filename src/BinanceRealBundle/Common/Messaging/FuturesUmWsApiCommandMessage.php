<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmWsRequestInterface;

/**
 * Futures UM WS API command envelope.
 *
 * @api
 */
final readonly class FuturesUmWsApiCommandMessage
{
    public function __construct(
        public FuturesUmWsRequestInterface $command,
    ) {
    }
}
