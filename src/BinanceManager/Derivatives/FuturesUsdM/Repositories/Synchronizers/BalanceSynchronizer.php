<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\AccountUpdateEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\AccountBalanceResponse;
use Empiriq\Contracts\Entities\BalanceEntity;
use React\Promise\PromiseInterface;

/**
 * @internal
 */
trait BalanceSynchronizer
{
    /**
     * @return PromiseInterface<self>
     * @internal This method is not intended for external use.
     */
    public function __synchronize(): PromiseInterface
    {
        return $this->connector->futuresUsdM()->accountBalanceV2()->then(function (AccountBalanceResponse $response) {
            foreach ($response->result as $balance) {
                $this->list[] = new BalanceEntity(
                    asset: $balance->asset,
                    balance: $balance->availableBalance,
                );
            }
            $this->dispatcher->addListener(AccountUpdateEvent::class, [$this, '__accountUpdate'], PHP_INT_MAX);

            return $this;
        });
    }

    /**
     * @param AccountUpdateEvent $event
     * @return void
     * @internal This method is not intended for external use.
     */
    public function __accountUpdate(AccountUpdateEvent $event): void
    {
        foreach ($event->updateData->balances as $balance) {
            foreach ($this->list as $index => $item) {
                if ($item->asset === $balance->asset) {
                    $item->balance = $balance->walletBalance;
                    $this->list[$index] = $item;
                }
            }
        }
    }
}
