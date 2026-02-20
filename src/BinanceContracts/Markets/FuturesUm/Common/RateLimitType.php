<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum RateLimitType: string
{
    case REQUEST_WEIGHT = 'REQUEST_WEIGHT';
    case ORDERS = 'ORDERS';
}
