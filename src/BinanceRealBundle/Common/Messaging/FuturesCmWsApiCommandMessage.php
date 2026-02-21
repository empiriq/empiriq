<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceContracts\Markets\FuturesCm\Requests\FuturesCmWsRequestInterface;

/**
 * Futures CM WS API command envelope.
 *
 * @api
 */
final readonly class FuturesCmWsApiCommandMessage
{
    public function __construct(
        public FuturesCmWsRequestInterface $command,
    ) {
    }
}
