<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Common;

enum OrderCancelResultType: string
{
    case FAILURE = 'FAILURE';
    case SUCCESS = 'SUCCESS';
}
