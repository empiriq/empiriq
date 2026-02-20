<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

enum OrderIdentifierType: string
{
    case EXCHANGE = 'EXCHANGE';
    case CLIENT = 'CLIENT';
}
