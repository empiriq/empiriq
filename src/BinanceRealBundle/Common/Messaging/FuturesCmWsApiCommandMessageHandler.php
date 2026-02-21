<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceRealBundle\Markets\FuturesCm\FuturesCm;
use React\Promise\PromiseInterface;

/**
 * Executes Futures CM WS commands.
 *
 * @api
 */
final readonly class FuturesCmWsApiCommandMessageHandler
{
    public function __construct(
        private FuturesCm $market,
    ) {
    }

    /**
     * @return PromiseInterface<object>
     */
    public function __invoke(FuturesCmWsApiCommandMessage $message): PromiseInterface
    {
        return $this->market->ws->send(
            method: $message->command->wsMethod(),
            permission: $message->command->permission(),
            type: $message->command->responseType(),
            payload: $message->command,
        );
    }
}
