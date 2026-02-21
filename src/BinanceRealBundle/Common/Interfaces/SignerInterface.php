<?php

namespace Empiriq\BinanceRealBundle\Common\Interfaces;

interface SignerInterface
{
    public function createSignature(array $params): string;
}
