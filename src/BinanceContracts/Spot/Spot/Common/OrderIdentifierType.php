<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum OrderIdentifierType: string
{
    case EXCHANGE = 'EXCHANGE';
    case CLIENT = 'CLIENT';
}
