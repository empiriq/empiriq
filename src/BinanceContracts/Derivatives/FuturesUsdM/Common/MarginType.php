<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum MarginType: string
{
    case CROSSED = 'CROSSED';
    case ISOLATED = 'ISOLATED';
}
