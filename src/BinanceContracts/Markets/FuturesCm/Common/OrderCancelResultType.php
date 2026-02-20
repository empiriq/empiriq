<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Common;

enum OrderCancelResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
