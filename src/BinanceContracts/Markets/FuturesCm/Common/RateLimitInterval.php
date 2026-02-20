<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Common;

enum RateLimitInterval: string
{
    case SECOND = 'SECOND';
    case MINUTE = 'MINUTE';
    case HOUR = 'HOUR';
    case DAY = 'DAY';
}
