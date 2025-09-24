<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

use Empiriq\BinanceContracts\Common\PermissionInterface;

/**
 * @see https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/request-security
 */
enum Permission: string implements PermissionInterface
{
    /** Public market data */
    case NONE = 'NONE';

    /** Other signed requests */
    case SIGNED = 'SIGNED';

    /** Trading on the exchange, placing and canceling orders */
    case TRADE = 'TRADE';

    /** Private account information, such as order status and your trading history */
    case USER_DATA = 'USER_DATA';

    /** Managing User Data Stream subscriptions */
    case USER_STREAM = 'USER_STREAM';

    public function requiresApiKey(): bool
    {
        return match ($this) {
            self::NONE => false,
            self::TRADE, self::USER_STREAM, self::USER_DATA, self::SIGNED => true,
        };
    }

    public function requiresSignature(): bool
    {
        return match ($this) {
            self::NONE, self::USER_STREAM => false,
            self::TRADE, self::USER_DATA, self::SIGNED => true,
        };
    }
}
