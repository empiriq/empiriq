<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

enum OrderNewResponseType: string
{
    case ACK = 'ACK';
    case RESULT = 'RESULT';
    case FULL = 'FULL';
}
