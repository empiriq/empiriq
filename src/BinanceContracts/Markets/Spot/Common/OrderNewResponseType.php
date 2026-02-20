<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

enum OrderNewResponseType: string
{
    case ACK = 'ACK';
    case RESULT = 'RESULT';
    case FULL = 'FULL';
}
