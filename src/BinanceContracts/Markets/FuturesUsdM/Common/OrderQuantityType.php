<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

enum OrderQuantityType: string
{
    case BASE = 'BASE';
    case QUOTE = 'QUOTE';
}
