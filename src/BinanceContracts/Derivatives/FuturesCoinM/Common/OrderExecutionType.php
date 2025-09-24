<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

/**
 * Это тип текущего события, которое вызвало отправку execution report. Он показывает причину события.
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
