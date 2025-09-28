<?php

namespace Empiriq\Contracts;

interface ExchangeConnectorInterface
{
    /**
     * @api Exchange Connector Configuration
     */
    public function getConfig(): array;
}
