<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum OrderCancelResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
