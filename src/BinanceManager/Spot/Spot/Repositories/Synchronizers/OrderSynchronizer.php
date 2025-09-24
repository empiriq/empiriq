<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Spot\Spot\Events\User\ExecutionReportEvent;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait OrderSynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        $this->dispatcher->addListener(ExecutionReportEvent::class, [$this, 'onExecutionReport'], PHP_INT_MAX);
        return resolve([]);
    }

    public function onExecutionReport(ExecutionReportEvent $event): void
    {
        $this->update($event);
    }
}
