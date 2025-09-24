<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum OrderIdentifierType: string
{
    case EXCHANGE = 'EXCHANGE';
    case CLIENT = 'CLIENT';
}
