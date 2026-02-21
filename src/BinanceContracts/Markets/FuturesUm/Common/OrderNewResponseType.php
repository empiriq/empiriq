<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

/**
 * Supported response modes for `order.place` in USD-M Futures WS API.
 */
enum OrderNewResponseType: string
{
    case ACK = 'ACK';
    case RESULT = 'RESULT';
}
