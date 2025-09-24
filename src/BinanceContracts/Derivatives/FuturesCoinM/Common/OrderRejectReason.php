<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

enum OrderRejectReason: string
{
    /**
     * The order was not rejected.
     */
    case NONE = 'NONE';

    /**
     * Account has insufficient balance for requested action.
     */
    case INSUFFICIENT_BALANCES = 'INSUFFICIENT_BALANCES';

    /**
     * Order would trigger immediately.
     */
    case STOP_PRICE_WOULD_TRIGGER_IMMEDIATELY = 'STOP_PRICE_WOULD_TRIGGER_IMMEDIATELY';

    /**
     * Order would immediately match and take.
     */
    case WOULD_MATCH_IMMEDIATELY = 'WOULD_MATCH_IMMEDIATELY';

    /**
     * The relationship of the prices for the orders is not correct.
     */
    case OCO_BAD_PRICES = 'OCO_BAD_PRICES';
}
