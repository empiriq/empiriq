<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

/**
 * class OrderStatus(str, Enum):
 * NEW = "NEW"
 * PARTIALLY_FILLED = "PARTIALLY_FILLED"
 * FILLED = "FILLED"
 * CANCELED = "CANCELED"
 * EXPIRED = "EXPIRED"
 * EXPIRED_IN_MATCH = "EXPIRED_IN_MATCH"
 */
enum OrderStatus: string
{
    case NEW = 'NEW';
    case PARTIALLY_FILLED = 'PARTIALLY_FILLED';
    case FILLED = 'FILLED';
    case CANCELED = 'CANCELED';
    case PENDING_CANCEL = 'PENDING_CANCEL';
    case REJECTED = 'REJECTED';
    case EXPIRED_IN_MATCH = 'EXPIRED_IN_MATCH';
}
