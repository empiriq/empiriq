<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Common;

enum OrderNewResponseType: string
{
    case ACK = 'ACK';
    case RESULT = 'RESULT';
    case FULL = 'FULL';
}
