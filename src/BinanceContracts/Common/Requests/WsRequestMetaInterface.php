<?php

namespace Empiriq\BinanceContracts\Common\Requests;

use Empiriq\BinanceContracts\Common\PermissionInterface;

/**
 * Metadata for WS API requests.
 *
 * @api
 */
interface WsRequestMetaInterface
{
    public function wsMethod(): string;

    public function permission(): PermissionInterface;

    /**
     * @return class-string
     */
    public function responseType(): string;
}
