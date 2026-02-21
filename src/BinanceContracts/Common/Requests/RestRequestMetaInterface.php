<?php

namespace Empiriq\BinanceContracts\Common\Requests;

use Empiriq\BinanceContracts\Common\PermissionInterface;

/**
 * Metadata for REST API requests.
 *
 * @api
 */
interface RestRequestMetaInterface
{
    public function httpMethod(): string;

    public function path(): string;

    public function permission(): PermissionInterface;

    /**
     * @return class-string
     */
    public function responseType(): string;
}
