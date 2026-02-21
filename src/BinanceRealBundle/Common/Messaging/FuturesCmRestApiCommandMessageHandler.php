<?php

namespace Empiriq\BinanceRealBundle\Common\Messaging;

use Empiriq\BinanceRealBundle\Markets\FuturesCm\FuturesCm;
use React\Promise\PromiseInterface;

/**
 * Executes Futures CM REST commands.
 *
 * @api
 */
final readonly class FuturesCmRestApiCommandMessageHandler
{
    public function __construct(
        private FuturesCm $market,
    ) {
    }

    /**
     * @return PromiseInterface<object>
     */
    public function __invoke(FuturesCmRestApiCommandMessage $message): PromiseInterface
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
