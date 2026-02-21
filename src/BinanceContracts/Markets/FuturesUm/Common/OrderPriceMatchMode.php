<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

/**
 * Price matching mode values for `priceMatch` on supported order types.
 */
enum OrderPriceMatchMode: string
{
    case NONE = 'NONE';
    case OPPONENT = 'OPPONENT';
    case OPPONENT_5 = 'OPPONENT_5';
    case OPPONENT_10 = 'OPPONENT_10';
    case OPPONENT_20 = 'OPPONENT_20';
    case QUEUE = 'QUEUE';
    case QUEUE_5 = 'QUEUE_5';
    case QUEUE_10 = 'QUEUE_10';
    case QUEUE_20 = 'QUEUE_20';

    // Legacy values kept for backward compatibility.
    case PESSIMISTIC = 'PESSIMISTIC';
    case OPTIMISTIC = 'OPTIMISTIC';
}
