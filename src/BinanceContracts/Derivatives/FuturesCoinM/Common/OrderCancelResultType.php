<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

enum OrderCancelResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
