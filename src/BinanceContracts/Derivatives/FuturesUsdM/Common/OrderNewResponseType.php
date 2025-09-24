<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum OrderNewResponseType: string
{
    case ACK = 'ACK';
    case RESULT = 'RESULT';
    case FULL = 'FULL';
}
