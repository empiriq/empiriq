<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

enum OrderIdentifierType: string
{
    case EXCHANGE = 'EXCHANGE';
    case CLIENT = 'CLIENT';
}
