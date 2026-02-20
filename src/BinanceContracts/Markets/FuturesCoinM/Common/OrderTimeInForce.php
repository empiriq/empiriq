<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Common;

enum OrderTimeInForce: string
{
    case GOOD_TILL_CANCEL = 'GTC';
    case IMMEDIATE_OR_CANCEL = 'IOC';
    case FILL_OR_KILL = 'FOK';
}
