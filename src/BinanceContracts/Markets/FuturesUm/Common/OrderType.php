<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

/**
 * USD-M Futures order types used by REST/WS trading APIs.
 *
 * Includes canonical futures values and legacy aliases kept for compatibility
 * with existing request/response DTOs.
 */
enum OrderType: string
{
    case LIMIT = 'LIMIT';
    case MARKET = 'MARKET';
    case STOP = 'STOP';
    case STOP_MARKET = 'STOP_MARKET';
    case TAKE_PROFIT_MARKET = 'TAKE_PROFIT_MARKET';
    case TRAILING_STOP_MARKET = 'TRAILING_STOP_MARKET';

    // Legacy aliases kept for backward compatibility in existing DTOs.
    case STOP_LOSS = 'STOP_LOSS';
    case STOP_LOSS_LIMIT = 'STOP_LOSS_LIMIT';
    case TAKE_PROFIT = 'TAKE_PROFIT';
    case TAKE_PROFIT_LIMIT = 'TAKE_PROFIT_LIMIT';
    case LIMIT_MAKER = 'LIMIT_MAKER';
}
