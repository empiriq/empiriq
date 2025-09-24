<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

enum RateLimitType: string
{
    case REQUEST_WEIGHT = 'REQUEST_WEIGHT';
    case ORDERS = 'ORDERS';
}
