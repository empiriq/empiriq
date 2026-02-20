<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

enum PositionSide: string
{
    case LONG = 'LONG';
    case SHORT = 'SHORT';
    case BOTH = 'BOTH';
}
