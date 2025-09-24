<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum OrderPriceMatchMode: string
{
    case OPPONENT = 'OPPONENT';
    case PESSIMISTIC = 'PESSIMISTIC';
    case OPTIMISTIC = 'OPTIMISTIC';
    case NONE = 'NONE';
}
