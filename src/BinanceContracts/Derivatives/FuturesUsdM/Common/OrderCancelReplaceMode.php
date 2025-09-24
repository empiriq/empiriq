<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum OrderCancelReplaceMode: string
{
    case STOP_ON_FAILURE = 'STOP_ON_FAILURE';
    case ALLOW_FAILURE = 'ALLOW_FAILURE';
}
