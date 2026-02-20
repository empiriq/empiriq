<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

enum MarginType: string
{
    case CROSSED = 'CROSSED';
    case ISOLATED = 'ISOLATED';
}
