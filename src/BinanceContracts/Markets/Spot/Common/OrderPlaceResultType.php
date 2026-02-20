<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

enum OrderPlaceResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
