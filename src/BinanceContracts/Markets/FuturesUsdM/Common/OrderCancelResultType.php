<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

enum OrderCancelResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
