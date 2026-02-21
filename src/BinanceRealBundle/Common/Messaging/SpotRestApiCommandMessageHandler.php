<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceRealBundle\Markets\Spot\Spot;
use React\Promise\PromiseInterface;

/**
 * Executes Spot REST commands.
 *
 * @api
 */
final readonly class SpotRestApiCommandMessageHandler
{
    public function __construct(
        private Spot $market,
    ) {
    }

    /**
     * @return PromiseInterface<object>
     */
    public function __invoke(SpotRestApiCommandMessage $message): PromiseInterface
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
