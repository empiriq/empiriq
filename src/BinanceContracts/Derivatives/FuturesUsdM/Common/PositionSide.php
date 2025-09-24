<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum PositionSide: string
{
    case LONG = 'LONG';
    case SHORT = 'SHORT';
    case BOTH = 'BOTH';
}
