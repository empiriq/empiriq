<?php

namespace Empiriq\Contracts\Entities;

class OrderHistoryEntity
{
    public $time;
    public $symbol;
    public $type;
    public $side;
    public $average;
    public $price;
    public $executed;
    public $amount;
    public $reduceOnly;
    public $postOnly;
    public $triggerConditions;
    public $status;
}
