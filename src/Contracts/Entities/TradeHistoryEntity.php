<?php

namespace Empiriq\Contracts\Entities;

class TradeHistoryEntity
{
    public $time;
    public $symbol;
    public $side;
    public $price;
    public $quantity;
    public $fee;
    public $role;
    public $realizedPnl;
}
