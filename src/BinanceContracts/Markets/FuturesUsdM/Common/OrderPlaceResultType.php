<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

enum OrderPlaceResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
