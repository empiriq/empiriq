<?php

namespace Empiriq\BinanceManagerBundle\Tests\Registries\Repositories\Spot;

use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WebsocketApi;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\BalanceRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function React\Async\await;

class BalanceRepositoryTest extends TestCase
{
    public function testDeserialize(): void
    {
        $this->assertTrue(true);
    }
}
