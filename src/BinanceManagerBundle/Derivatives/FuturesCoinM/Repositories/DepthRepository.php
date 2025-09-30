<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCoinMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\DepthSynchronizer;

class DepthRepository implements FuturesCoinMRepositoryInterface
{
    use DepthSynchronizer;
}
