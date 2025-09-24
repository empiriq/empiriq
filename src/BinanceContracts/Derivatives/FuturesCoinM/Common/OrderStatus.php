<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

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
