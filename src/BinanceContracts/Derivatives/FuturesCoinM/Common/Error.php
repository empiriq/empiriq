<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

/**
 * @link https://developers.binance.com/docs/derivatives/coin-margined-futures/error-code
 */
enum Error: int
{
    // --- 10xx - General Server or Network issues ---

    /**
     * An unknown error occurred while processing the request.
     */
    case UNKNOWN = -1000;

    /**
     * Internal error; unable to process your request. Please try again.
     */
    case DISCONNECTED = -1001;

    /**
     * You are not authorized to execute this request.
     */
    case UNAUTHORIZED = -1002;

    /**
     * Too many requests; limit exceeded.
     * Example: %s requests per minute.
     * Way too many requests; IP banned until %s.
     * Use websocket for live updates to avoid bans.
     */
    case TOO_MANY_REQUESTS = -1003;

    /**
     * This IP is already on the white list.
     */
    case DUPLICATE_IP = -1004;

    /**
     * No such IP has been white listed.
     */
    case NO_SUCH_IP = -1005;

    /**
     * An unexpected response was received from the message bus. Execution status unknown.
     */
    case UNEXPECTED_RESP = -1006;

    /**
     * Timeout waiting for response from backend server.
     * Send status unknown; execution status unknown.
     */
    case TIMEOUT = -1007;

    /**
     * Server is currently overloaded with other requests. Please try again in a few minutes.
     */
    case SERVICE_UNAVAILABLE = -1008;

    /**
     * ERROR_MSG_RECEIVED.
     */
    case ERROR_MSG_RECEIVED = -1010;

    /**
     * This IP cannot access this route.
     */
    case NON_WHITE_LIST = -1011;

    /**
     * INVALID_MESSAGE.
     */
    case INVALID_MESSAGE = -1013;

    /**
     * Unsupported order combination.
     */
    case UNKNOWN_ORDER_COMPOSITION = -1014;

    /**
     * Too many new orders.
     * Example: limit %s orders per %s.
     */
    case TOO_MANY_ORDERS = -1015;

    /**
     * This service is no longer available.
     */
    case SERVICE_SHUTTING_DOWN = -1016;

    /**
     * This operation is not supported.
     */
    case UNSUPPORTED_OPERATION = -1020;

    /**
     * Invalid timestamp.
     * Example: request outside of recvWindow or 1000ms ahead of server time.
     */
    case INVALID_TIMESTAMP = -1021;

    /**
     * Signature for this request is not valid.
     */
    case INVALID_SIGNATURE = -1022;

    /**
     * Start time is greater than end time.
     */
    case START_TIME_GREATER_THAN_END_TIME = -1023;

    /**
     * Illegal characters found in a parameter.
     * Example: '%s'; legal range is '%s'.
     */
    case ILLEGAL_CHARS = -1100;

    /**
     * Too many parameters sent for this endpoint.
     * Expected '%s', received '%s'.
     * Duplicate values detected.
     */
    case TOO_MANY_PARAMETERS = -1101;

    /**
     * A mandatory parameter was not sent, was empty/null, or malformed.
     * Example: '%s' missing or invalid.
     * Or both '%s' and '%s' empty/null.
     */
    case MANDATORY_PARAM_EMPTY_OR_MALFORMED = -1102;

    /**
     * An unknown parameter was sent.
     */
    case UNKNOWN_PARAM = -1103;

    /**
     * Not all sent parameters were read.
     */
    case UNREAD_PARAMETERS = -1104;

    /**
     * A parameter was empty.
     */
    case PARAM_EMPTY = -1105;

    /**
     * A parameter was sent when not required.
     */
    case PARAM_NOT_REQUIRED = -1106;

    /**
     * Invalid asset.
     */
    case BAD_ASSET = -1108;

    /**
     * Invalid account.
     */
    case BAD_ACCOUNT = -1109;

    /**
     * Invalid symbolType.
     */
    case BAD_INSTRUMENT_TYPE = -1110;

    /**
     * Precision is over the maximum defined for this asset.
     */
    case BAD_PRECISION = -1111;

    /**
     * No orders on book for symbol.
     */
    case NO_DEPTH = -1112;

    /**
     * Withdrawal amount must be negative.
     */
    case WITHDRAW_NOT_NEGATIVE = -1113;

    /**
     * TimeInForce parameter sent when not required.
     */
    case TIF_NOT_REQUIRED = -1114;

    /**
     * Invalid timeInForce.
     */
    case INVALID_TIF = -1115;

    /**
     * Invalid orderType.
     */
    case INVALID_ORDER_TYPE = -1116;

    /**
     * Invalid side.
     */
    case INVALID_SIDE = -1117;

    /**
     * New client order ID was empty.
     */
    case EMPTY_NEW_CL_ORD_ID = -1118;

    /**
     * Original client order ID was empty.
     */
    case EMPTY_ORG_CL_ORD_ID = -1119;

    /**
     * Invalid interval.
     */
    case BAD_INTERVAL = -1120;

    /**
     * Invalid symbol.
     */
    case BAD_SYMBOL = -1121;

    /**
     * This listenKey does not exist. Please use POST /fapi/v1/listenKey to recreate listenKey.
     */
    case INVALID_LISTEN_KEY = -1125;

    /**
     * Lookup interval is too big. More than %s hours between startTime and endTime.
     */
    case MORE_THAN_XX_HOURS = -1127;

    /**
     * Combination of optional parameters invalid.
     */
    case OPTIONAL_PARAMS_BAD_COMBO = -1128;

    /**
     * Invalid data sent for a parameter.
     * Data sent for parameter '%s' is not valid.
     */
    case INVALID_PARAMETER = -1130;

    /**
     * Invalid newOrderRespType.
     */
    case INVALID_NEW_ORDER_RESP_TYPE = -1136;

    // --- 20xx - Processing Issues ---

    /**
     * NEW_ORDER_REJECTED.
     */
    case NEW_ORDER_REJECTED = -2010;

    /**
     * CANCEL_REJECTED.
     */
    case CANCEL_REJECTED = -2011;

    /**
     * Order does not exist.
     */
    case NO_SUCH_ORDER = -2013;

    /**
     * API-key format invalid.
     */
    case BAD_API_KEY_FMT = -2014;

    /**
     * Invalid API-key, IP, or permissions for action.
     */
    case REJECTED_MBX_KEY = -2015;

    /**
     * No trading window could be found for the symbol. Try ticker/24hrs instead.
     */
    case NO_TRADING_WINDOW = -2016;

    /**
     * Balance is insufficient.
     */
    case BALANCE_NOT_SUFFICIENT = -2018;

    /**
     * Margin is insufficient.
     */
    case MARGIN_NOT_SUFFICIENT = -2019;

    /**
     * Unable to fill.
     */
    case UNABLE_TO_FILL = -2020;

    /**
     * Order would immediately trigger.
     */
    case ORDER_WOULD_IMMEDIATELY_TRIGGER = -2021;

    /**
     * ReduceOnly Order is rejected.
     */
    case REDUCE_ONLY_REJECT = -2022;

    /**
     * User in liquidation mode now.
     */
    case USER_IN_LIQUIDATION = -2023;

    /**
     * Position is not sufficient.
     */
    case POSITION_NOT_SUFFICIENT = -2024;

    /**
     * Reach max open order limit.
     */
    case MAX_OPEN_ORDER_EXCEEDED = -2025;

    /**
     * This OrderType is not supported when reduceOnly.
     */
    case REDUCE_ONLY_ORDER_TYPE_NOT_SUPPORTED = -2026;

    /**
     * Exceeded the maximum allowable position at current leverage.
     */
    case MAX_LEVERAGE_RATIO = -2027;

    /**
     * Leverage is smaller than permitted: insufficient margin balance.
     */
    case MIN_LEVERAGE_RATIO = -2028;

    /**
     * Invalid order status.
     */
    case INVALID_ORDER_STATUS = -4000;

    /**
     * Price less than 0.
     */
    case PRICE_LESS_THAN_ZERO = -4001;

    /**
     * Price greater than max price.
     */
    case PRICE_GREATER_THAN_MAX_PRICE = -4002;

    /**
     * Quantity less than zero.
     */
    case QTY_LESS_THAN_ZERO = -4003;

    /**
     * Quantity less than min quantity.
     */
    case QTY_LESS_THAN_MIN_QTY = -4004;

    /**
     * Quantity greater than max quantity.
     */
    case QTY_GREATER_THAN_MAX_QTY = -4005;

    /**
     * Stop price less than zero.
     */
    case STOP_PRICE_LESS_THAN_ZERO = -4006;

    /**
     * Stop price greater than max price.
     */
    case STOP_PRICE_GREATER_THAN_MAX_PRICE = -4007;

    /**
     * Tick size less than zero.
     */
    case TICK_SIZE_LESS_THAN_ZERO = -4008;

    /**
     * Max price less than min price.
     */
    case MAX_PRICE_LESS_THAN_MIN_PRICE = -4009;

    /**
     * Max qty less than min qty.
     */
    case MAX_QTY_LESS_THAN_MIN_QTY = -4010;

    /**
     * Step size less than zero.
     */
    case STEP_SIZE_LESS_THAN_ZERO = -4011;

    /**
     * Maximum orders less than zero.
     */
    case MAX_NUM_ORDERS_LESS_THAN_ZERO = -4012;

    /**
     * Price less than min price.
     */
    case PRICE_LESS_THAN_MIN_PRICE = -4013;

    /**
     * Price not increased by tick size.
     */
    case PRICE_NOT_INCREASED_BY_TICK_SIZE = -4014;

    /**
     * Client order id is not valid.
     * Client order id length should not be more than 36 chars.
     */
    case INVALID_CL_ORD_ID_LEN = -4015;

    /**
     * Price is higher than mark price multiplier cap.
     */
    case PRICE_HIGHTER_THAN_MULTIPLIER_UP = -4016;

    /**
     * Multiplier up less than zero.
     */
    case MULTIPLIER_UP_LESS_THAN_ZERO = -4017;

    /**
     * Multiplier down less than zero.
     */
    case MULTIPLIER_DOWN_LESS_THAN_ZERO = -4018;

    /**
     * Composite scale too large.
     */
    case COMPOSITE_SCALE_OVERFLOW = -4019;

    /**
     * Target strategy invalid for orderType '%s', reduceOnly '%b'.
     */
    case TARGET_STRATEGY_INVALID = -4020;

    /**
     * Invalid depth limit.
     * '%s' is not valid depth limit.
     */
    case INVALID_DEPTH_LIMIT = -4021;

    /**
     * Market status sent is not valid.
     */
    case WRONG_MARKET_STATUS = -4022;

    /**
     * Qty not increased by step size.
     */
    case QTY_NOT_INCREASED_BY_STEP_SIZE = -4023;

    /**
     * Price is lower than mark price multiplier floor.
     */
    case PRICE_LOWER_THAN_MULTIPLIER_DOWN = -4024;

    /**
     * Multiplier decimal less than zero.
     */
    case MULTIPLIER_DECIMAL_LESS_THAN_ZERO = -4025;

    /**
     * Commission invalid. %s less than zero. %s absolute value greater than %s
     */
    case COMMISSION_INVALID = -4026;

    /**
     * Invalid account type.
     */
    case INVALID_ACCOUNT_TYPE = -4027;

    /**
     * Invalid leverage. Leverage %s is not valid. Leverage %s already exist with %s
     */
    case INVALID_LEVERAGE = -4028;

    /**
     * Tick size precision is invalid.
     */
    case INVALID_TICK_SIZE_PRECISION = -4029;

    /**
     * Step size precision is invalid.
     */
    case INVALID_STEP_SIZE_PRECISION = -4030;

    /**
     * Invalid parameter working type. Invalid parameter working type: %s
     */
    case INVALID_WORKING_TYPE = -4031;

    /**
     * Exceed maximum cancel order size. Invalid parameter working type: %s
     */
    case EXCEED_MAX_CANCEL_ORDER_SIZE = -4032;

    /**
     * Insurance account not found.
     */
    case INSURANCE_ACCOUNT_NOT_FOUND = -4033;

    /**
     * Balance Type is invalid.
     */
    case INVALID_BALANCE_TYPE = -4044;

    /**
     * Reach max stop order limit.
     */
    case MAX_STOP_ORDER_EXCEEDED = -4045;

    /**
     * No need to change margin type.
     */
    case NO_NEED_TO_CHANGE_MARGIN_TYPE = -4046;

    /**
     * Margin type cannot be changed if there exists open orders.
     */
    case THERE_EXISTS_OPEN_ORDERS = -4047;

    /**
     * Margin type cannot be changed if there exists position.
     */
    case THERE_EXISTS_QUANTITY = -4048;

    /**
     * Add margin only support for isolated position.
     */
    case ADD_ISOLATED_MARGIN_REJECT = -4049;

    /**
     * Cross balance insufficient.
     */
    case CROSS_BALANCE_INSUFFICIENT = -4050;

    /**
     * Isolated balance insufficient.
     */
    case ISOLATED_BALANCE_INSUFFICIENT = -4051;

    /**
     * No need to change auto add margin.
     */
    case NO_NEED_TO_CHANGE_AUTO_ADD_MARGIN = -4052;

    /**
     * Auto add margin only support for isolated position.
     */
    case AUTO_ADD_CROSSED_MARGIN_REJECT = -4053;

    /**
     * Cannot add position margin: position is 0.
     */
    case ADD_ISOLATED_MARGIN_NO_POSITION_REJECT = -4054;

    /**
     * Amount must be positive.
     */
    case AMOUNT_MUST_BE_POSITIVE = -4055;

    /**
     * Invalid api key type.
     */
    case INVALID_API_KEY_TYPE = -4056;

    /**
     * Invalid api public key.
     */
    case INVALID_RSA_PUBLIC_KEY = -4057;

    /**
     * maxPrice and priceDecimal too large, please check.
     */
    case MAX_PRICE_TOO_LARGE = -4058;

    /**
     * No need to change position side.
     */
    case NO_NEED_TO_CHANGE_POSITION_SIDE = -4059;

    /**
     * Invalid position side.
     */
    case INVALID_POSITION_SIDE = -4060;

    /**
     * Order's position side does not match user's setting.
     */
    case POSITION_SIDE_NOT_MATCH = -4061;

    /**
     * Invalid or improper reduceOnly value.
     */
    case REDUCE_ONLY_CONFLICT = -4062;

    /**
     * Position side cannot be changed if there exists open orders.
     */
    case POSITION_SIDE_CHANGE_EXISTS_OPEN_ORDERS = -4067;

    /**
     * Position side cannot be changed if there exists position.
     */
    case POSITION_SIDE_CHANGE_EXISTS_QUANTITY = -4068;

    /**
     * Invalid number of batch place orders.
     */
    case INVALID_BATCH_PLACE_ORDER_SIZE = -4082;

    /**
     * Fail to place batch orders.
     */
    case PLACE_BATCH_ORDERS_FAIL = -4083;

    /**
     * Method is not allowed currently. Upcoming soon.
     */
    case UPCOMING_METHOD = -4084;

    /**
     * Invalid price spread threshold.
     */
    case INVALID_PRICE_SPREAD_THRESHOLD = -4086;

    /**
     * Invalid pair.
     */
    case INVALID_PAIR = -4087;

    /**
     * Invalid time interval.
     * Maximum time interval is %s days.
     */
    case INVALID_TIME_INTERVAL = -4088;

    /**
     * User can only place reduce only order.
     */
    case REDUCE_ONLY_ORDER_PERMISSION = -4089;

    /**
     * User can not place order currently.
     */
    case NO_PLACE_ORDER_PERMISSION = -4090;

    /**
     * Invalid contract type.
     */
    case INVALID_CONTRACT_TYPE = -4104;

    /**
     * clientTranId is not valid.
     * Client tran id length should be less than 64 chars.
     */
    case INVALID_CLIENT_TRAN_ID_LEN = -4110;

    /**
     * clientTranId is duplicated.
     * Client tran id should be unique within 7 days.
     */
    case DUPLICATED_CLIENT_TRAN_ID = -4111;

    /**
     * ReduceOnly Order Failed. Please check your existing position and open orders.
     */
    case REDUCE_ONLY_MARGIN_CHECK_FAILED = -4112;

    /**
     * The counterparty's best price does not meet the PERCENT_PRICE filter limit.
     */
    case MARKET_ORDER_REJECT = -4113;

    /**
     * Invalid activation price.
     */
    case INVALID_ACTIVATION_PRICE = -4135;

    /**
     * Quantity must be zero with closePosition equals true.
     */
    case QUANTITY_EXISTS_WITH_CLOSE_POSITION = -4137;

    /**
     * Reduce only must be true with closePosition equals true.
     */
    case REDUCE_ONLY_MUST_BE_TRUE = -4138;

    /**
     * Order type can not be market if it's unable to cancel.
     */
    case ORDER_TYPE_CANNOT_BE_MKT = -4139;

    /**
     * REJECT: take profit or stop order will be triggered immediately.
     */
    case STRATEGY_INVALID_TRIGGER_PRICE = -4142;

    /**
     * Leverage reduction is not supported in Isolated Margin Mode with open positions.
     */
    case ISOLATED_LEVERAGE_REJECT_WITH_POSITION = -4150;

    /**
     * Price is higher than stop price multiplier cap.
     * Limit price can't be higher than %s.
     */
    case PRICE_HIGHTER_THAN_STOP_MULTIPLIER_UP = -4151;

    /**
     * Price is lower than stop price multiplier floor.
     * Limit price can't be lower than %s.
     */
    case PRICE_LOWER_THAN_STOP_MULTIPLIER_DOWN = -4152;

    /**
     * Stop price is higher than price multiplier cap.
     * Stop price can't be higher than %s.
     */
    case STOP_PRICE_HIGHER_THAN_PRICE_MULTIPLIER_LIMIT = -4154;

    /**
     * Stop price is lower than price multiplier floor.
     * Stop price can't be lower than %s.
     */
    case STOP_PRICE_LOWER_THAN_PRICE_MULTIPLIER_LIMIT = -4155;

    /**
     * Order's notional must be no smaller than one (unless you choose reduce only).
     * Order's notional must be no smaller than %s (unless you choose reduce only).
     */
    case MIN_NOTIONAL = -4178;

    /**
     * Trade forbidden due to Cooling-off Period.
     */
    case COOLING_OFF_PERIOD = -4192;

    /**
     * Intermediate Personal Verification is required for adjusting leverage over 20x.
     */
    case ADJUST_LEVERAGE_KYC_FAILED = -4194;

    /**
     * More than 20x leverage is available one month after account registration.
     */
    case ADJUST_LEVERAGE_ONE_MONTH_FAILED = -4195;

    /**
     * Only limit order is supported.
     */
    case LIMIT_ORDER_ONLY = -4196;

    /**
     * No need to modify the order.
     */
    case SAME_ORDER = -4197;

    /**
     * Exceed maximum modify order limit.
     */
    case EXCEED_MAX_MODIFY_ORDER_LIMIT = -4198;

    /**
     * Symbol is not in trading status. Order amendment is not permitted.
     */
    case MOVE_ORDER_NOT_ALLOWED_SYMBOL_REASON = -4199;

    /**
     * More than 20x leverage is available 30 days after Futures account registration.
     * More than 20x leverage is available %s days after Futures account registration.
     */
    case ADJUST_LEVERAGE_X_DAYS_FAILED = -4200;

    /**
     * Users in this country has limited adjust leverage.
     * Users in your location/country can only access a maximum leverage of %s.
     */
    case ADJUST_LEVERAGE_KYC_LIMIT = -4201;

    /**
     * Current symbol leverage cannot exceed 20 when using position limit adjustment service.
     */
    case ADJUST_LEVERAGE_ACCOUNT_SYMBOL_FAILED = -4202;

    /**
     * Timestamp for this request is outside the ME recvWindow.
     */
    case ME_INVALID_TIMESTAMP = -4188;
}
