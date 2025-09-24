<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

enum RateLimitInterval: string
{
    case SECOND = 'SECOND';
    case MINUTE = 'MINUTE';
    case HOUR = 'HOUR';
    case DAY = 'DAY';
}
