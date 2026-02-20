<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Common;

enum OrderPlaceResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
