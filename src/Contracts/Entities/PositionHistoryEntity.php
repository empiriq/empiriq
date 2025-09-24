<?php

namespace Empiriq\Contracts\Entities;

class PositionHistoryEntity
{
    public $symbol;
    public $side;
    public $closingPnl;
    public $entryPrice;
    public $avgClosePrice;
    public $maxOpenInterest;
    public $closedVol;
    public $opened;
    public $closed;
}
