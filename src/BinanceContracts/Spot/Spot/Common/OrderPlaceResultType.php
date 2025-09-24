<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum OrderPlaceResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
