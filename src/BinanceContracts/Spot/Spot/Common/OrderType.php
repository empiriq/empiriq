<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum OrderType: string
{
    case LIMIT = 'LIMIT';
    case MARKET = 'MARKET';
    case STOP_LOSS = 'STOP_LOSS';
    case STOP_LOSS_LIMIT = 'STOP_LOSS_LIMIT';
    case TAKE_PROFIT = 'TAKE_PROFIT';
    case TAKE_PROFIT_LIMIT = 'TAKE_PROFIT_LIMIT';
    case LIMIT_MAKER = 'LIMIT_MAKER';
}
