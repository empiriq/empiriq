<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum RateLimitType: string
{
    case REQUEST_WEIGHT = 'REQUEST_WEIGHT';
    case ORDERS = 'ORDERS';
}
