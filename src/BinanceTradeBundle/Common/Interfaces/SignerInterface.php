<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

interface SignerInterface
{
    public function createSignature(array $params): string;
}
