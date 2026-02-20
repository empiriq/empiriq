<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum OrderIdentifierType: string
{
    case EXCHANGE = 'EXCHANGE';
    case CLIENT = 'CLIENT';
}
