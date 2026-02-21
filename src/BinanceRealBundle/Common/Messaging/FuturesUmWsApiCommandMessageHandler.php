<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceRealBundle\Markets\FuturesUm\FuturesUm;
use React\Promise\PromiseInterface;

/**
 * Executes Futures UM WS commands.
 *
 * @api
 */
final readonly class FuturesUmWsApiCommandMessageHandler
{
    public function __construct(
        private FuturesUm $market,
    ) {
    }

    /**
     * @return PromiseInterface<object>
     */
    public function __invoke(FuturesUmWsApiCommandMessage $message): PromiseInterface
    {
        return $this->market->ws->send(
            method: $message->command->wsMethod(),
            permission: $message->command->permission(),
            type: $message->command->responseType(),
            payload: $message->command,
        );
    }
}
