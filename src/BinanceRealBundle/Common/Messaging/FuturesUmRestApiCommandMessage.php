<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\FuturesUmRestRequestInterface;

/**
 * Futures UM REST API command envelope.
 *
 * @api
 */
final readonly class FuturesUmRestApiCommandMessage
{
    public function __construct(
        public FuturesUmRestRequestInterface $command,
    ) {
    }
}
