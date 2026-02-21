<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotWsRequestInterface;

/**
 * Spot WS API command envelope.
 *
 * @api
 */
final readonly class SpotWsApiCommandMessage
{
    public function __construct(
        public SpotWsRequestInterface $command,
    ) {
    }
}
