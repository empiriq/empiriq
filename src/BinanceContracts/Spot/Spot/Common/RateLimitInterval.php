<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum RateLimitInterval: string
{
    case SECOND = 'SECOND';
    case MINUTE = 'MINUTE';
    case HOUR = 'HOUR';
    case DAY = 'DAY';
}
