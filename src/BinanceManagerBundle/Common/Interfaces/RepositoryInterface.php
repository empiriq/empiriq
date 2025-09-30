<?php

namespace Empiriq\BinanceManagerBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

interface RepositoryInterface
{
    public function __synchronize(): PromiseInterface;
}
