<?php

namespace Empiriq\Contracts\Common;

enum OrderSide: string
{
    case BUY = 'BUY';
    case SELL = 'SELL';
}
