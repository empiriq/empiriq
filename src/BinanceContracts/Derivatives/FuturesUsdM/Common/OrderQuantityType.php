<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum OrderQuantityType: string
{
    case BASE = 'BASE';
    case QUOTE = 'QUOTE';
}
