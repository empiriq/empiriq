<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

/**
 * Это тип текущего события, которое вызвало отправку execution report. Он показывает причину события.
 *
 * class ExecutionType(str, Enum):
 * NEW = "NEW"
 * CANCELED = "CANCELED"
 * CALCULATED = "CALCULATED"  # Liquidation Execution
 * EXPIRED = "EXPIRED"
 * TRADE = "TRADE"
 * AMENDMENT = "AMENDMENT"  # Order Modified
 */
enum OrderExecutionType: string
{
    case NEW = 'NEW'; // Новый ордер создан
    case CANCELED = 'CANCELED'; // Ордер отменён
    case REPLACED = 'REPLACED'; // Ордер заменён
    case REJECTED = 'REJECTED'; // Ордер отклонён
    case TRADE = 'TRADE'; // Частичное или полное исполнение сделки
    case EXPIRED = 'EXPIRED'; // Истёк срок действия (например, по таймеру или TIF)
    case CALCULATED = 'CALCULATED'; // Расчётная запись, например при редактировании OCO
}
