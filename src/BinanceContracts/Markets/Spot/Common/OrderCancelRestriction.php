<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

enum OrderCancelRestriction: string
{
    case ONLY_NEW = 'ONLY_NEW';
    case ONLY_PARTIALLY_FILLED = 'ONLY_PARTIALLY_FILLED';
    case FILL_OR_KILL = 'FOK';
    case GOOD_TILL_CROSSING = 'GTX';
}
