<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Events\User;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\EventInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderExecutionType;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderRejectReason;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderStatus;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderType;

/**
 * @link https://developers.binance.com/docs/binance-spot-api-docs/user-data-stream#order-update
 */
readonly class ExecutionReportEvent implements EventInterface
{
    /**
     * @param int $time
     * @param string $symbol
     * @param string $clientOrderId
     * @param OrderSide $side
     * @param OrderType $type
     * @param OrderTimeInForce $timeInForce
     * @param float $quantity
     * @param float $price
     * @param float $stopPrice
     * @param float $icebergQty
     * @param int $orderListId
     * @param string $origClientOrderId Original client order ID; This is the ID of the order being canceled
     * @param OrderExecutionType $executionType Current execution type
     * @param OrderStatus $status Current order status
     * @param OrderRejectReason $rejectReason Order reject reason
     * @param int $orderId
     * @param float $lastQty Last executed quantity
     * @param float $cumulativeQty Cumulative filled quantity
     * @param float $lastPrice Last executed price
     * @param float $commissionAmount
     * @param float|null $commissionAsset
     * @param int $transactionTime
     * @param int $tradeId
     * @param int $preventedMatchId Prevented Match ID. This is only visible if the order expired due to STP
     * @param int $executionId
     * @param bool $inOrderBook Is the order on the book?
     * @param bool $isMaker Is this trade the maker side?
     * @param int $creationTime Order creation time
     * @param float $cumulativeQuoteQty Cumulative quote asset transacted quantity
     * @param float $lastQuoteQty Last quote asset transacted quantity (i.e. lastPrice * lastQty)
     * @param float $quoteOrderQty Quote Order Quantity
     * @param int $workingTime Working Time; This is only visible if the order has been placed on the book.
     * @param OrderPreventionMode $selfTradePreventionMode
     */
    public function __construct(
        public int $time,
        public string $symbol,
        public string $clientOrderId,
        public OrderSide $side,
        public OrderType $type,
        public OrderTimeInForce $timeInForce,
        public float $quantity,
        public float $price,
        public float $stopPrice,
        public float $icebergQty,
        public int $orderListId,
        public string $origClientOrderId,
        public OrderExecutionType $executionType,
        public OrderStatus $status,
        public OrderRejectReason $rejectReason,
        public int $orderId,
        public float $lastQty,
        public float $cumulativeQty,
        public float $lastPrice,
        public float $commissionAmount,
        public ?float $commissionAsset,
        public int $transactionTime,
        public int $tradeId,
        public int $preventedMatchId,
        public int $executionId,
        public bool $inOrderBook,
        public bool $isMaker,
        public int $creationTime,
        public float $cumulativeQuoteQty,
        public float $lastQuoteQty,
        public float $quoteOrderQty,
        public int $workingTime,
        public OrderPreventionMode $selfTradePreventionMode,
    ) {
    }
}
