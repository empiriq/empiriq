<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceContracts\Markets\FuturesCm\Requests\FuturesCmRestRequestInterface;

/**
 * Futures CM REST API command envelope.
 *
 * @api
 */
final readonly class FuturesCmRestApiCommandMessage
{
    public function __construct(
        public FuturesCmRestRequestInterface $command,
    ) {
    }
}
