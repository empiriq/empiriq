<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum RateLimitInterval: string
{
    case SECOND = 'SECOND';
    case MINUTE = 'MINUTE';
    case HOUR = 'HOUR';
    case DAY = 'DAY';
}
