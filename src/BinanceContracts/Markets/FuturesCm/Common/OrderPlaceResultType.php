<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Common;

enum OrderPlaceResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
