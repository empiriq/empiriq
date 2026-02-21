<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

/**
 * Time-in-force values for USD-M Futures orders.
 */
enum OrderTimeInForce: string
{
    case GOOD_TILL_CANCEL = 'GTC';
    case IMMEDIATE_OR_CANCEL = 'IOC';
    case FILL_OR_KILL = 'FOK';
    case GOOD_TILL_CROSSING = 'GTX';
    case GOOD_TILL_DATE = 'GTD';
}
