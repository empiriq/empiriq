<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Spot\Spot\Events\User\BalanceUpdateEvent;
use Empiriq\BinanceContracts\Spot\Spot\Events\User\ExternalLockUpdateEvent;
use Empiriq\BinanceContracts\Spot\Spot\Events\User\OutboundAccountPositionEvent;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Account\AccountStatusResponse;
use Empiriq\BinanceManager\Common\Helpers\Changes;
use Empiriq\Contracts\Entities\BalanceEntity;
use Empiriq\Contracts\Events\BalanceChangedEvent;
use React\Promise\PromiseInterface;
use ReflectionException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait BalanceSynchronizer
{
    /**
     * @return PromiseInterface<self>
     * @throws ExceptionInterface
     * @internal This method is not intended for external use.
     */
    public function __synchronize(): PromiseInterface
    {
        $this->sender->accountStatus()->then(function (AccountStatusResponse $response) {
            $this->list = $response->result->balances;
        });
        $this->dispatcher->addListener(OutboundAccountPositionEvent::class, [$this, '__accountUpdate'], PHP_INT_MAX);
        $this->dispatcher->addListener(BalanceUpdateEvent::class, [$this, '__balanceUpdate'], PHP_INT_MAX);
        $this->dispatcher->addListener(ExternalLockUpdateEvent::class, [$this, '__externalLockUpdate'], PHP_INT_MAX);
        return resolve($this);
    }

    /**
     * @param OutboundAccountPositionEvent $event
     * @return void
     * @internal This method is not intended for external use.
     */
    public function __accountUpdate(OutboundAccountPositionEvent $event): void
    {
        $this->list = $event->balances;
    }

    /**
     * @param BalanceUpdateEvent $event
     * @return void
     * @internal This method is not intended for external use.
     */
    public function __balanceUpdate(BalanceUpdateEvent $event): void
    {
        $entity = $this->findOneByAsset($event->asset);
        $entity->free += $event->delta;
        $this->__update($entity);
    }

    /**
     * @param ExternalLockUpdateEvent $event
     * @return void
     * @throws ReflectionException
     * @internal This method is not intended for external use.
     */
    public function __externalLockUpdate(ExternalLockUpdateEvent $event): void
    {
        $entity = $this->findOneByAsset($event->asset);
        $oldEntity = clone $entity;
        $entity->locked += $event->delta;
        $this->__update($entity);
        $this->dispatcher->dispatch(
            event: new BalanceChangedEvent($entity, Changes::build($oldEntity, $entity)),
            eventName: self::EVENT_BALANCE_CHANGED,
        );
    }

    private function __update(BalanceEntity $entity): void
    {
        foreach ($this->list as $index => $item) {
            if ($item->asset === $entity->asset) {
                $this->list[$index] = $entity;
                return;
            }
        }
        $this->list[] = $entity;
    }
}
