<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Spot\Spot\Events\User\ExecutionReportEvent;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait OrderSynchronizer
{
    #[\Override]
    public function __synchronize(): PromiseInterface
    {
        $this->dispatcher->addListener(ExecutionReportEvent::class, [$this, 'onExecutionReport'], PHP_INT_MAX);
        return resolve($this);
    }

    public function onExecutionReport(ExecutionReportEvent $event): void
    {
        $this->update($event);
    }
}
