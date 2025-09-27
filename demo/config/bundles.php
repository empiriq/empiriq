<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Empiriq\BinanceBackTradeBundle\ConnectorBundle::class => ['backtrade' => true],
    Empiriq\BinanceTradeBundle\ConnectorBundle::class => ['realtrade' => true],
    Empiriq\Server\ServerBundle::class => ['realtrade' => false],
];
