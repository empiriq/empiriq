<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Events\User\Payload;

enum MarginType: string
{
    case CROSS = 'cross';
    case ISOLATED = 'isolated';
}
