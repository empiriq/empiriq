<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum OrderCancelResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
