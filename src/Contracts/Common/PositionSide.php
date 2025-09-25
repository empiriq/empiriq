<?php

namespace Empiriq\Contracts\Common;

enum PositionSide: string
{
    case LONG = 'LONG';
    case SHORT = 'SHORT';
    case BOTH = 'BOTH';
}
