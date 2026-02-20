<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum OrderNewResponseType: string
{
    case ACK = 'ACK';
    case RESULT = 'RESULT';
    case FULL = 'FULL';
}
