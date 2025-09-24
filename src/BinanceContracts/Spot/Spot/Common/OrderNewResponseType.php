<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum OrderNewResponseType: string
{
    case ACK = 'ACK';
    case RESULT = 'RESULT';
    case FULL = 'FULL';
}
