<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceRealBundle\Markets\FuturesUm\FuturesUm;
use React\Promise\PromiseInterface;

/**
 * Executes Futures UM REST commands.
 *
 * @api
 */
final readonly class FuturesUmRestApiCommandMessageHandler
{
    public function __construct(
        private FuturesUm $market,
    ) {
    }

    /**
     * @return PromiseInterface<object>
     */
    public function __invoke(FuturesUmRestApiCommandMessage $message): PromiseInterface
    {
        return $this->market->rest->send(
            method: $message->command->httpMethod(),
            path: $message->command->path(),
            permission: $message->command->permission(),
            type: $message->command->responseType(),
            payload: $message->command,
        );
    }
}
