<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

enum OrderQuantityType: string
{
    case BASE = 'BASE';
    case QUOTE = 'QUOTE';
}
