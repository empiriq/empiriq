<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Common;

enum OrderIdentifierType: string
{
    case EXCHANGE = 'EXCHANGE';
    case CLIENT = 'CLIENT';
}
