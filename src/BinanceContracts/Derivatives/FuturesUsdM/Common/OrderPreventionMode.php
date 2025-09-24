<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

/**
 *
 * class STPMode(str, Enum):
 * EXPIRE_TAKER = "EXPIRE_TAKER"
 * EXPIRE_MAKER = "EXPIRE_MAKER"
 * EXPIRE_BOTH = "EXPIRE_BOTH"
 * NONE = "NONE"
 */
enum OrderPreventionMode: string
{
    case NONE = 'NONE';
    case EXPIRE_MAKER = 'EXPIRE_MAKER';
    case EXPIRE_TAKER = 'EXPIRE_TAKER';
    case EXPIRE_BOTH = 'EXPIRE_BOTH';
    case DECREMENT = 'DECREMENT';
}
