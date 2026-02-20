<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

enum OrderWorkingType: string
{
    case MARK_PRICE = 'MARK_PRICE';
    case CONTRACT_PRICE = 'CONTRACT_PRICE';
}
