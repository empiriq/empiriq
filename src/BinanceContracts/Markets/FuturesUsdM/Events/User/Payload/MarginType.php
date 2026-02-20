<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Events\User\Payload;

enum MarginType: string
{
    case CROSS = 'cross';
    case ISOLATED = 'isolated';
}
