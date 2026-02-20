<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum OrderPriceMatchMode: string
{
    case OPPONENT = 'OPPONENT';
    case PESSIMISTIC = 'PESSIMISTIC';
    case OPTIMISTIC = 'OPTIMISTIC';
    case NONE = 'NONE';
}
