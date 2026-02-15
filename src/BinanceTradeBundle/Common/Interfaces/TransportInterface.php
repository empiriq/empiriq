<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

interface TransportInterface
{
    public function isLoggedIn(): bool;
}
