<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Common;

enum RateLimitType: string
{
    case REQUEST_WEIGHT = 'REQUEST_WEIGHT';
    case ORDERS = 'ORDERS';
}
