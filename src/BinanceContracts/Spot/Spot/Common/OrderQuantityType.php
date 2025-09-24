<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

enum OrderQuantityType: string
{
    case BASE = 'BASE';
    case QUOTE = 'QUOTE';
}
