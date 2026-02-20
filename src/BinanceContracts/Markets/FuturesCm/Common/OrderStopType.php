<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Common;

enum OrderStopType: string
{
    case FIX_PRICE = 'FIX_PRICE';
    case TRAILING_DELTA = 'TRAILING_DELTA';
}
