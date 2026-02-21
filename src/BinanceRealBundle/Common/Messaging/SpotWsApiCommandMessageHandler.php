<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceRealBundle\Markets\Spot\Spot;
use React\Promise\PromiseInterface;

/**
 * Executes Spot WS commands.
 *
 * @api
 */
final readonly class SpotWsApiCommandMessageHandler
{
    public function __construct(
        private Spot $market,
    ) {
    }

    /**
     * @return PromiseInterface<object>
     */
    public function __invoke(SpotWsApiCommandMessage $message): PromiseInterface
    {
        return $this->market->ws->send(
            method: $message->command->wsMethod(),
            permission: $message->command->permission(),
            type: $message->command->responseType(),
            payload: $message->command,
        );
    }
}
