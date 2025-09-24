<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

enum OrderWorkingType: string
{
    case MARK_PRICE = 'MARK_PRICE';
    case CONTRACT_PRICE = 'CONTRACT_PRICE';
}
