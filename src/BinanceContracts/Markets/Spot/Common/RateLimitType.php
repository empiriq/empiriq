<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

enum RateLimitType: string
{
    case REQUEST_WEIGHT = 'REQUEST_WEIGHT';
    case ORDERS = 'ORDERS';
}
