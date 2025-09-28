<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Empiriq\BinanceBackTradeBundle\BinanceBackTradeBundle::class => ['backtrade' => true],
    Empiriq\BinanceTradeBundle\BinanceTradeBundle::class => ['realtrade' => true],
    Empiriq\TerminalBundle\TerminalBundle::class => ['all' => true],
];
