<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

enum OrderCancelResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
