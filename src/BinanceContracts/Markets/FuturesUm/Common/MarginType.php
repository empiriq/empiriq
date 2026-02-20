<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum MarginType: string
{
    case CROSSED = 'CROSSED';
    case ISOLATED = 'ISOLATED';
}
