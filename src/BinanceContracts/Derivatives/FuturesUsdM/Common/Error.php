<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

/**
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/error-code
 */
enum Error: int
{
    // --- 10xx General Server or Network issues ---

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
     * Too many requests; current limit is %s requests per minute.
     * Please use the websocket for live updates to avoid polling the API.
     * Way too many requests; IP banned until %s.
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
     * An unexpected response was received from the message bus.
     * Execution status unknown.
     */
    case UNEXPECTED_RESP = -1006;

    /**
     * Timeout waiting for response from backend server.
     * Send status unknown; execution status unknown.
     */
    case TIMEOUT = -1007;

    /**
     * Server is currently overloaded with other requests.
     * Please try again in a few minutes.
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
     * Too many new orders; current limit is %s orders per %s.
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
     * Timestamp for this request is outside of the recvWindow.
     * Timestamp for this request was 1000ms ahead of the server's time.
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
     * Not found, unauthenticated, or unauthorized.
     */
    case NOT_FOUND = -1099;

    // --- 11xx Request issues ---

    /**
     * Illegal characters found in a parameter.
     * Illegal characters found in parameter '%s'; legal range is '%s'.
     */
    case ILLEGAL_CHARS = -1100;

    /**
     * Too many parameters sent for this endpoint.
     * Too many parameters; expected '%s' and received '%s'.
     * Duplicate values for a parameter detected.
     */
    case TOO_MANY_PARAMETERS = -1101;

    /**
     * A mandatory parameter was not sent, was empty/null, or malformed.
     * Mandatory parameter '%s' was not sent, was empty/null, or malformed.
     * Param '%s' or '%s' must be sent, but both were empty/null!
     */
    case MANDATORY_PARAM_EMPTY_OR_MALFORMED = -1102;

    /**
     * An unknown parameter was sent.
     */
    case UNKNOWN_PARAM = -1103;

    /**
     * Not all sent parameters were read.
     * Not all sent parameters were read; read '%s' parameter(s) but was sent '%s'.
     */
    case UNREAD_PARAMETERS = -1104;

    /**
     * A parameter was empty.
     * Parameter '%s' was empty.
     */
    case PARAM_EMPTY = -1105;

    /**
     * A parameter was sent when not required.
     * Parameter '%s' sent when not required.
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
     * Invalid symbol status.
     */
    case INVALID_SYMBOL_STATUS = -1122;

    /**
     * This listenKey does not exist.
     */
    case INVALID_LISTEN_KEY = -1125;

    /**
     * This asset is not supported.
     */
    case ASSET_NOT_SUPPORTED = -1126;

    /**
     * Lookup interval is too big.
     * More than %s hours between startTime and endTime.
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

    // --- 20xx Processing issues ---

    /**
     * NEW_ORDER_REJECTED
     */
    case NEW_ORDER_REJECTED = -2010;

    /**
     * CANCEL_REJECTED
     */
    case CANCEL_REJECTED = -2011;

    /**
     * Batch cancel failure.
     */
    case CANCEL_ALL_FAIL = -2012;

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
     * No trading window could be found for the symbol.
     */
    case NO_TRADING_WINDOW = -2016;

    /**
     * API Keys are locked on this account.
     */
    case API_KEYS_LOCKED = -2017;

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

    // --- 40xx Filters and other Issues ---

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
     * Max number of orders less than zero.
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
     * Length should not be more than 36 characters.
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
     * Target strategy invalid.
     * Example: invalid for orderType '%s', reduceOnly '%b'.
     */
    case TARGET_STRATEGY_INVALID = -4020;

    /**
     * Invalid depth limit.
     * '%s' is not a valid depth limit.
     */
    case INVALID_DEPTH_LIMIT = -4021;

    /**
     * Market status sent is not valid.
     */
    case WRONG_MARKET_STATUS = -4022;

    /**
     * Quantity not increased by step size.
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
     * Commission invalid.
     * Example: %s less than zero, or absolute value greater than %s.
     */
    case COMMISSION_INVALID = -4026;

    /**
     * Invalid account type.
     */
    case INVALID_ACCOUNT_TYPE = -4027;

    /**
     * Invalid leverage.
     * Examples: leverage %s is not valid, or already exists with %s.
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
     * Invalid parameter working type.
     * Example: invalid working type: %s
     */
    case INVALID_WORKING_TYPE = -4031;

    /**
     * Exceed maximum cancel order size.
     */
    case EXCEED_MAX_CANCEL_ORDER_SIZE = -4032;

    /**
     * Insurance account not found.
     */
    case INSURANCE_ACCOUNT_NOT_FOUND = -4033;

    /**
     * Balance type is invalid.
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
     * Margin type cannot be changed if there exist open orders.
     */
    case THERE_EXISTS_OPEN_ORDERS = -4047;

    /**
     * Margin type cannot be changed if there exists position.
     */
    case THERE_EXISTS_QUANTITY = -4048;

    /**
     * Add margin only supported for isolated position.
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
     * Auto add margin only supported for isolated position.
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
     * Invalid API key type.
     */
    case INVALID_API_KEY_TYPE = -4056;

    /**
     * Invalid API public key.
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
     * Invalid options request type.
     */
    case INVALID_OPTIONS_REQUEST_TYPE = -4063;

    /**
     * Invalid options time frame.
     */
    case INVALID_OPTIONS_TIME_FRAME = -4064;

    /**
     * Invalid options amount.
     */
    case INVALID_OPTIONS_AMOUNT = -4065;

    /**
     * Invalid options event type.
     */
    case INVALID_OPTIONS_EVENT_TYPE = -4066;

    /**
     * Position side cannot be changed if there exist open orders.
     */
    case POSITION_SIDE_CHANGE_EXISTS_OPEN_ORDERS = -4067;

    /**
     * Position side cannot be changed if there exists position.
     */
    case POSITION_SIDE_CHANGE_EXISTS_QUANTITY = -4068;

    /**
     * Invalid options premium fee.
     */
    case INVALID_OPTIONS_PREMIUM_FEE = -4069;

    /**
     * Client options id is not valid.
     * Must be less than 32 chars.
     */
    case INVALID_CL_OPTIONS_ID_LEN = -4070;

    /**
     * Invalid options direction.
     */
    case INVALID_OPTIONS_DIRECTION = -4071;

    /**
     * Premium fee is not updated, reject order.
     */
    case OPTIONS_PREMIUM_NOT_UPDATE = -4072;

    /**
     * Input premium fee is less than 0, reject order.
     */
    case OPTIONS_PREMIUM_INPUT_LESS_THAN_ZERO = -4073;

    /**
     * Order amount is bigger than upper boundary or less than 0, reject order.
     */
    case OPTIONS_AMOUNT_BIGGER_THAN_UPPER = -4074;

    /**
     * Output premium fee is less than 0, reject order
     */
    case OPTIONS_PREMIUM_OUTPUT_ZERO = -4075;

    /**
     * original fee is too much higher than last fee
     */
    case OPTIONS_PREMIUM_TOO_DIFF = -4076;

    /**
     * place order amount has reached to limit, reject order
     */
    case OPTIONS_PREMIUM_REACH_LIMIT = -4077;

    /**
     * options internal error
     */
    case OPTIONS_COMMON_ERROR = -4078;

    /**
     * invalid options id
     * invalid options id: %s
     * duplicate options id %d for user %d
     */
    case INVALID_OPTIONS_ID = -4079;

    /**
     * user not found
     * user not found with id: %s
     */
    case OPTIONS_USER_NOT_FOUND = -4080;

    /**
     * options not found
     * options not found with id: %s
     */
    case OPTIONS_NOT_FOUND = -4081;

    /**
     * Invalid number of batch place orders.
     * Invalid number of batch place orders: %s
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
     * Invalid notional limit coefficient
     */
    case INVALID_NOTIONAL_LIMIT_COEF = -4085;

    /**
     * Invalid price spread threshold
     */
    case INVALID_PRICE_SPREAD_THRESHOLD = -4086;

    /**
     * User can only place reduce only order
     */
    case REDUCE_ONLY_ORDER_PERMISSION = -4087;

    /**
     * User can not place order currently
     */
    case NO_PLACE_ORDER_PERMISSION = -4088;

    /**
     * Invalid contract type
     */
    case INVALID_CONTRACT_TYPE = -4104;

    /**
     * Inactive account
     * Transfer any amount of asset to future wallet to reactive
     */
    case INACTIVE_ACCOUNT = -4109;

    /**
     * clientTranId is not valid
     * Client tran id length should be less than 64 chars
     */
    case INVALID_CLIENT_TRAN_ID_LEN = -4114;

    /**
     * clientTranId is duplicated
     * Client tran id should be unique within 7 days
     */
    case DUPLICATED_CLIENT_TRAN_ID = -4115;

    /**
     * clientOrderId is duplicated
     */
    case DUPLICATED_CLIENT_ORDER_ID = -4116;

    /**
     * stop order is triggering
     */
    case STOP_ORDER_TRIGGERING = -4117;

    /**
     * ReduceOnly Order Failed. Please check your existing position and open orders
     */
    case REDUCE_ONLY_MARGIN_CHECK_FAILED = -4118;

    /**
     * The counterparty's best price does not meet the PERCENT_PRICE filter limit
     */
    case MARKET_ORDER_REJECT = -4131;

    /**
     * Invalid activation price
     */
    case INVALID_ACTIVATION_PRICE = -4135;

    /**
     * Quantity must be zero with closePosition equals true
     */
    case QUANTITY_EXISTS_WITH_CLOSE_POSITION = -4137;

    /**
     * Reduce only must be true with closePosition equals true
     */
    case REDUCE_ONLY_MUST_BE_TRUE = -4138;

    /**
     * Order type can not be market if it's unable to cancel
     */
    case ORDER_TYPE_CANNOT_BE_MKT = -4139;

    /**
     * Invalid symbol status for opening position
     */
    case INVALID_OPENING_POSITION_STATUS = -4140;

    /**
     * Symbol is closed
     */
    case SYMBOL_ALREADY_CLOSED = -4141;

    /**
     * REJECT: take profit or stop order will be triggered immediately
     */
    case STRATEGY_INVALID_TRIGGER_PRICE = -4142;

    /**
     * Invalid pair
     */
    case INVALID_PAIR = -4144;

    /**
     * Leverage reduction is not supported in Isolated Margin Mode with open positions
     */
    case ISOLATED_LEVERAGE_REJECT_WITH_POSITION = -4161;

    /**
     * Order's notional must be no smaller than 5.0 (unless you choose reduce only)
     * Order's notional must be no smaller than %s (unless you choose reduce only)
     */
    case MIN_NOTIONAL = -4164;

    /**
     * Invalid time interval
     * Maximum time interval is %s days
     */
    case INVALID_TIME_INTERVAL = -4165;

    /**
     * Unable to adjust to Multi-Assets mode with symbols of USDⓈ-M Futures under isolated-margin mode.
     */
    case ISOLATED_REJECT_WITH_JOINT_MARGIN = -4167;

    /**
     * Unable to adjust to isolated-margin mode under the Multi-Assets mode.
     */
    case JOINT_MARGIN_REJECT_WITH_ISOLATED = -4168;

    /**
     * Unable to adjust Multi-Assets Mode with insufficient margin balance in USDⓈ-M Futures.
     */
    case JOINT_MARGIN_REJECT_WITH_MB = -4169;

    /**
     * Unable to adjust Multi-Assets Mode with open orders in USDⓈ-M Futures.
     */
    case JOINT_MARGIN_REJECT_WITH_OPEN_ORDER = -4170;

    /**
     * Adjusted asset mode is currently set and does not need to be adjusted repeatedly.
     */
    case NO_NEED_TO_CHANGE_JOINT_MARGIN = -4171;

    /**
     * Unable to adjust Multi-Assets Mode with a negative wallet balance
     * of margin available asset in USDⓈ-M Futures account.
     */
    case JOINT_MARGIN_REJECT_WITH_NEGATIVE_BALANCE = -4172;

    /**
     * Price is higher than stop price multiplier cap.
     * Limit price can't be higher than %s.
     */
    case ISOLATED_REJECT_WITH_JOINT_MARGIN_2 = -4183;

    /**
     * Price is lower than stop price multiplier floor.
     * Limit price can't be lower than %s.
     */
    case PRICE_LOWER_THAN_STOP_MULTIPLIER_DOWN = -4184;

    /**
     * Trade forbidden due to Cooling-off Period.
     */
    case COOLING_OFF_PERIOD = -4192;

    /**
     * Intermediate Personal Verification is required for adjusting leverage over 20x
     */
    case ADJUST_LEVERAGE_KYC_FAILED = -4202;

    /**
     * More than 20x leverage is available one month after account registration.
     */
    case ADJUST_LEVERAGE_ONE_MONTH_FAILED = -4203;

    /**
     * More than 20x leverage is available %s days after Futures account registration.
     */
    case ADJUST_LEVERAGE_X_DAYS_FAILED = -4205;

    /**
     * Users in this country has limited adjust leverage.
     */
    case ADJUST_LEVERAGE_KYC_LIMIT = -4206;

    /**
     * Current symbol leverage cannot exceed 20 when using position limit adjustment service.
     */
    case ADJUST_LEVERAGE_ACCOUNT_SYMBOL_FAILED = -4208;

    /**
     * The max leverage of Symbol is 20x
     */
    case ADJUST_LEVERAGE_SYMBOL_FAILED = -4209;

    /**
     * Stop price is higher than price multiplier cap.
     */
    case STOP_PRICE_HIGHER_THAN_PRICE_MULTIPLIER_LIMIT = -4210;

    /**
     * Stop price is lower than price multiplier floor.
     */
    case STOP_PRICE_LOWER_THAN_PRICE_MULTIPLIER_LIMIT = -4211;

    /**
     * Futures Trading Quantitative Rules violated, only reduceOnly order is allowed, please try again later.
     */
    case TRADING_QUANTITATIVE_RULE = -4400;

    /**
     * Futures Trading Risk Control Rules of large position holding violated,
     * only reduceOnly order is allowed, please reduce the position.
     */
    case LARGE_POSITION_SYM_RULE = -4401;

    /**
     * Feature is not available in your region due to compliance restrictions.
     */
    case COMPLIANCE_BLACK_SYMBOL_RESTRICTION = -4402;

    /**
     * Dear user, as per our Terms of Use and compliance with local regulations,
     * the leverage can only up to 10x in your region
     */
    case ADJUST_LEVERAGE_COMPLIANCE_FAILED = -4403;

    // --- 50xx - Order Execution Issues ---

    /**
     * Due to the order could not be filled immediately, the FOK order has been rejected.
     */
    case FOK_ORDER_REJECT = -5021;

    /**
     * Due to the order could not be executed as maker, the Post Only order will be rejected.
     */
    case GTX_ORDER_REJECT = -5022;

    /**
     * Symbol is not in trading status. Order amendment is not permitted.
     */
    case MOVE_ORDER_NOT_ALLOWED_SYMBOL_REASON = -5024;

    /**
     * Only limit order is supported.
     */
    case LIMIT_ORDER_ONLY = -5025;

    /**
     * Exceed maximum modify order limit.
     */
    case EXCEED_MAXIMUM_MODIFY_ORDER_LIMIT = -5026;

    /**
     * No need to modify the order.
     */
    case SAME_ORDER = -5027;

    /**
     * Timestamp for this request is outside of the ME recvWindow.
     */
    case ME_RECVWINDOW_REJECT = -5028;

    /**
     * Order's notional must be no smaller than %s
     */
    case MODIFICATION_MIN_NOTIONAL = -5029;

    /**
     * Invalid price match
     */
    case INVALID_PRICE_MATCH = -5037;

    /**
     * Price match only supports order type: LIMIT, STOP AND TAKE_PROFIT
     */
    case UNSUPPORTED_ORDER_TYPE_PRICE_MATCH = -5038;

    /**
     * Invalid self trade prevention mode
     */
    case INVALID_SELF_TRADE_PREVENTION_MODE = -5039;

    /**
     * The goodTillDate timestamp must be greater than the current time plus 600 seconds
     * and smaller than 253402300799000 (UTC 9999-12-31 23:59:59)
     */
    case FUTURE_GOOD_TILL_DATE = -5040;

    /**
     * No depth matches this BBO order
     */
    case BBO_ORDER_REJECT = -5041;

    /**
     * A pending modification already exists for this order.
     */
    case EXISTING_PENDING_MODIFICATION = -5043;
}
