<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceContracts\Markets\Spot\Requests\SpotRestRequestInterface;

/**
 * Spot REST API command envelope.
 *
 * @api
 */
final readonly class SpotRestApiCommandMessage
{
    public function __construct(
        public SpotRestRequestInterface $command,
    ) {
    }
}
