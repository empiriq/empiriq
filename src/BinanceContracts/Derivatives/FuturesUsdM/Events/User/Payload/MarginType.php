<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\Payload;

enum MarginType: string
{
    case CROSS = 'cross';
    case ISOLATED = 'isolated';
}
