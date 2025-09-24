<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

/**
 * @link https://developers.binance.com/docs/binance-spot-api-docs/errors
 */
enum Error: int
{
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
     * Too many requests queued.
     * Too much request weight used; current limit is %s request weight per %s.
     * Way too much request weight used; IP banned until %s.
     * Please use WebSocket Streams for live updates to avoid polling the API.
     */
    case TOO_MANY_REQUESTS = -1003;

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
    case SERVER_BUSY = -1008;

    /**
     * ERROR_MSG_RECEIVED
     * @see https://developers.binance.com/docs/binance-spot-api-docs/errors#messages-for--1010-error_msg_received--2010-new_order_rejected--2011-cancel_rejected-and--2038-order_amend_rejected
     */
    case ERROR_MSG_RECEIVED = -1010;

    /**
     * The request is rejected by the API (didn't reach the Matching Engine).
     * Potential error messages: Filter Failures / Failures during order placement.
     */
    case INVALID_MESSAGE = -1013;

    /**
     * Unsupported order combination.
     */
    case UNKNOWN_ORDER_COMPOSITION = -1014;

    /**
     * Too many new orders.
     * Current limit is %s orders per %s.
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
     * Or was 1000ms ahead of the server's time.
     */
    case INVALID_TIMESTAMP = -1021;

    /**
     * Signature for this request is not valid.
     */
    case INVALID_SIGNATURE = -1022;

    /**
     * SenderCompId(49) is currently in use.
     * Concurrent use of the same SenderCompId within one account is not allowed.
     */
    case COMP_ID_IN_USE = -1033;

    /**
     * Too many concurrent connections. Current limit is '%s'.
     * Too many connection attempts for account; current limit is %s per '%s'.
     * Too many connection attempts from IP; current limit is %s per '%s'.
     */
    case TOO_MANY_CONNECTIONS = -1034;

    /**
     * Please send Logout<5> message to close the session.
     */
    case LOGGED_OUT = -1035;

    /**
     * Illegal characters found in a parameter.
     * Example: Illegal characters in '%s'; legal range is '%s'.
     */
    case ILLEGAL_CHARS = -1100;

    /**
     * Too many parameters sent for this endpoint.
     * Expected '%s' and received '%s'.
     * Duplicate values for a parameter detected.
     */
    case TOO_MANY_PARAMETERS = -1101;

    /**
     * A mandatory parameter was not sent, was empty/null, or malformed.
     * E.g. '%s' missing, empty or malformed.
     * Param '%s' or '%s' must be sent, but both were empty/null.
     * '%s' contains unexpected value. Cannot be greater than %s.
     */
    case MANDATORY_PARAM_EMPTY_OR_MALFORMED = -1102;

    /**
     * An unknown parameter was sent.
     * Undefined Tag.
     */
    case UNKNOWN_PARAM = -1103;

    /**
     * Not all sent parameters were read.
     * Example: Read '%s' parameter(s) but was sent '%s'.
     */
    case UNREAD_PARAMETERS = -1104;

    /**
     * A parameter was empty.
     * Example: '%s' was empty.
     */
    case PARAM_EMPTY = -1105;

    /**
     * A parameter was sent when not required.
     * Example: '%s' or tag '%s' sent when not required.
     */
    case PARAM_NOT_REQUIRED = -1106;

    /**
     * Parameter '%s' overflowed.
     */
    case PARAM_OVERFLOW = -1108;

    /**
     * Parameter '%s' has too much precision.
     */
    case BAD_PRECISION = -1111;

    /**
     * No orders on book for symbol.
     */
    case NO_DEPTH = -1112;

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
     * Invalid symbolStatus.
     */
    case INVALID_SYMBOLSTATUS = -1122;

    /**
     * This listenKey does not exist.
     */
    case INVALID_LISTEN_KEY = -1125;

    /**
     * Lookup interval is too big.
     * Example: More than %s hours between startTime and endTime.
     */
    case MORE_THAN_XX_HOURS = -1127;

    /**
     * Combination of optional parameters invalid.
     * Recommendation: '%s' and '%s' must both be sent.
     * Fields [%s] must be sent together or omitted entirely.
     * Invalid MDEntryType (269) combination. BID and OFFER must be requested together.
     */
    case OPTIONAL_PARAMS_BAD_COMBO = -1128;

    /**
     * Invalid data sent for a parameter.
     * Example: Data sent for parameter '%s' is not valid.
     */
    case INVALID_PARAMETER = -1130;

    /**
     * strategyType was less than 1000000.
     * TargetStrategy (847) was less than 1000000.
     */
    case BAD_STRATEGY_TYPE = -1134;

    /**
     * Invalid JSON Request.
     * Example: JSON sent for parameter '%s' is not valid.
     */
    case INVALID_JSON = -1135;

    /**
     * Invalid ticker type.
     */
    case INVALID_TICKER_TYPE = -1139;

    /**
     * cancelRestrictions has to be either ONLY_NEW or ONLY_PARTIALLY_FILLED.
     */
    case INVALID_CANCEL_RESTRICTIONS = -1145;

    /**
     * Symbol is present multiple times in the list.
     */
    case DUPLICATE_SYMBOLS = -1151;

    /**
     * Invalid X-MBX-SBE header; expected <SCHEMA_ID>:<VERSION>.
     */
    case INVALID_SBE_HEADER = -1152;

    /**
     * Unsupported SBE schema ID or version specified in the X-MBX-SBE header.
     */
    case UNSUPPORTED_SCHEMA_ID = -1153;

    /**
     * SBE is not enabled.
     */
    case SBE_DISABLED = -1155;

    /**
     * Order type not supported in OCO.
     * Example: The order type provided in aboveType and/or belowType is not supported.
     */
    case OCO_ORDER_TYPE_REJECTED = -1158;

    /**
     * Parameter '%s' not supported if aboveTimeInForce/belowTimeInForce is not GTC.
     * If order type is STOP_LOSS_LIMIT and icebergQty provided, timeInForce must be GTC.
     * TimeInForce (59) must be GTC (1) when MaxFloor (111) is used.
     */
    case OCO_ICEBERGQTY_TIMEINFORCE = -1160;

    /**
     * Unable to encode the response in SBE schema 'x'.
     * Please use schema 'y' or higher.
     */
    case DEPRECATED_SCHEMA = -1161;

    /**
     * A limit order in a buy OCO must be below.
     */
    case BUY_OCO_LIMIT_MUST_BE_BELOW = -1165;

    /**
     * A limit order in a sell OCO must be above.
     */
    case SELL_OCO_LIMIT_MUST_BE_ABOVE = -1166;

    /**
     * At least one OCO order must be contingent.
     */
    case BOTH_OCO_ORDERS_CANNOT_BE_LIMIT = -1168;

    /**
     * Invalid tag number.
     */
    case INVALID_TAG_NUMBER = -1169;

    /**
     * Tag '%s' not defined for this message type.
     */
    case TAG_NOT_DEFINED_IN_MESSAGE = -1170;

    /**
     * Tag '%s' appears more than once.
     */
    case TAG_APPEARS_MORE_THAN_ONCE = -1171;

    /**
     * Tag '%s' specified out of required order.
     */
    case TAG_OUT_OF_ORDER = -1172;

    /**
     * Repeating group '%s' fields out of order.
     */
    case GROUP_FIELDS_OUT_OF_ORDER = -1173;

    /**
     * Component '%s' is incorrectly populated on '%s' order.
     * Recommendation: '%s'
     */
    case INVALID_COMPONENT = -1174;

    /**
     * Continuation of sequence numbers to new session is currently unsupported.
     * Sequence numbers must be reset for each new session.
     */
    case RESET_SEQ_NUM_SUPPORT = -1175;

    /**
     * Logon<A> should only be sent once.
     */
    case ALREADY_LOGGED_IN = -1176;

    /**
     * Garbled FIX message.
     * Examples:
     * - CheckSum(10) contains incorrect value.
     * - BeginString (8) is not the first tag.
     * - MsgType (35) is not the third tag.
     * - BodyLength (9) incorrect.
     * - Only printable ASCII + SOH allowed.
     * - Tag specified without a value.
     */
    case GARBLED_MESSAGE = -1177;

    /**
     * SenderCompId(49) contains an incorrect value.
     * The SenderCompID value should not change throughout the lifetime of a session.
     */
    case BAD_SENDER_COMPID = -1178;

    /**
     * MsgSeqNum(34) contains an unexpected value.
     * Expected: '%d'.
     */
    case BAD_SEQ_NUM = -1179;

    /**
     * Logon<A> must be the first message in the session.
     */
    case EXPECTED_LOGON = -1180;

    /**
     * Too many messages; current limit is '%d' messages per '%s'.
     */
    case TOO_MANY_MESSAGES = -1181;

    /**
     * Conflicting fields: [%s].
     */
    case PARAMS_BAD_COMBO = -1182;

    /**
     * Requested operation is not allowed in DropCopy sessions.
     */
    case NOT_ALLOWED_IN_DROP_COPY_SESSIONS = -1183;

    /**
     * DropCopy sessions are not supported on this server.
     * Please reconnect to a drop copy server.
     */
    case DROP_COPY_SESSION_NOT_ALLOWED = -1184;

    /**
     * Only DropCopy sessions are supported on this server.
     * Either reconnect to order entry server or send DropCopyFlag (9406).
     */
    case DROP_COPY_SESSION_REQUIRED = -1185;

    /**
     * Requested operation is not allowed in order entry sessions.
     */
    case NOT_ALLOWED_IN_ORDER_ENTRY_SESSIONS = -1186;

    /**
     * Requested operation is not allowed in market data sessions.
     */
    case NOT_ALLOWED_IN_MARKET_DATA_SESSIONS = -1187;

    /**
     * Incorrect NumInGroup count for repeating group '%s'.
     */
    case INCORRECT_NUM_IN_GROUP_COUNT = -1188;

    /**
     * Group '%s' contains duplicate entries.
     */
    case DUPLICATE_ENTRIES_IN_A_GROUP = -1189;

    /**
     * Invalid request ID.
     * MDReqID (262) already in use or does not match any active subscription.
     */
    case INVALID_REQUEST_ID = -1190;

    /**
     * Too many subscriptions.
     * Max '%s' subscriptions per connection.
     * Or subscription already active for symbol='%s', id='%s'.
     */
    case TOO_MANY_SUBSCRIPTIONS = -1191;

    /**
     * Invalid value for time unit; expected MICROSECOND or MILLISECOND.
     */
    case INVALID_TIME_UNIT = -1194;

    /**
     * A stop loss order in a buy OCO must be above.
     */
    case BUY_OCO_STOP_LOSS_MUST_BE_ABOVE = -1196;

    /**
     * A stop loss order in a sell OCO must be below.
     */
    case SELL_OCO_STOP_LOSS_MUST_BE_BELOW = -1197;

    /**
     * A take profit order in a buy OCO must be below.
     */
    case BUY_OCO_TAKE_PROFIT_MUST_BE_BELOW = -1198;

    /**
     * A take profit order in a sell OCO must be above.
     */
    case SELL_OCO_TAKE_PROFIT_MUST_BE_ABOVE = -1199;

    /**
     * Invalid pegPriceType.
     */
    case INVALID_PEG_PRICE_TYPE = -1210;

    /**
     * Invalid pegOffsetType.
     */
    case INVALID_PEG_OFFSET_TYPE = -1211;

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
     * No trading window could be found for the symbol.
     * Try ticker/24hrs instead.
     */
    case NO_TRADING_WINDOW = -2016;

    /**
     * Order was canceled or expired with no executed qty over 90 days ago and has been archived.
     */
    case ORDER_ARCHIVED = -2026;

    /**
     * User Data Stream subscription already active.
     */
    case SUBSCRIPTION_ACTIVE = -2035;

    /**
     * User Data Stream subscription not active.
     */
    case SUBSCRIPTION_INACTIVE = -2036;


    /**
     * ORDER_AMEND_REJECTED
     * @see https://developers.binance.com/docs/binance-spot-api-docs/errors#messages-for--1010-error_msg_received--2010-new_order_rejected--2011-cancel_rejected-and--2038-order_amend_rejected
     */
    case ORDER_AMEND_REJECTED = -2038;

    /**
     * Client order ID is not correct for this order ID.
     */
    case CLIENT_ORDER_ID_INVALID = -2039;

    /**
     * Maximum subscription ID reached for this connection.
     */
    case MAXIMUM_SUBSCRIPTION_IDS = -2042;

    // --- Errors regarding placing orders via cancelReplace ---

    /**
     * This code is sent when either the cancellation of order failed or new order placement failed but not both.
     */
    case ORDER_CANCEL_REPLACE_PARTIALLY_FAILED = -2021;

    /**
     * This code is sent when both the cancellation of the order failed and the new order placement failed.
     */
    case ORDER_CANCEL_REPLACE_FAILED = -2022;
}
