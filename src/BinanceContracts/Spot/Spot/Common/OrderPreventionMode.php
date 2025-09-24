<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum OrderPreventionMode: string
{
    case NONE = 'NONE';
    case EXPIRE_MAKER = 'EXPIRE_MAKER';
    case EXPIRE_TAKER = 'EXPIRE_TAKER';
    case EXPIRE_BOTH = 'EXPIRE_BOTH';
    case DECREMENT = 'DECREMENT';
}
