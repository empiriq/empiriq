<?php

namespace Empiriq\BinanceContracts\Common;

interface PermissionInterface
{
    public function requiresApiKey(): bool;

    public function requiresSignature(): bool;
}
