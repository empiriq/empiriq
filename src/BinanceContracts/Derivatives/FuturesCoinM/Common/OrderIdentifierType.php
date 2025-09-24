<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

enum OrderIdentifierType: string
{
    case EXCHANGE = 'EXCHANGE';
    case CLIENT = 'CLIENT';
}
