<?php

namespace Empiriq\BinanceTradeBundle\Common\Helpers;

use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;

/**
 * Sanitizes sensitive request data before logging.
 * Supports full or partial masking of values.
 */
readonly class Sanitizer implements SanitizerInterface
{
    /**
     * @var list<string> Lowercased sensitive keys.
     */
    private array $normalizedKeys;

    /**
     * @param list<string> $sensitiveKeys Keys to sanitize (case-insensitive).
     * @param bool $partialMask Whether to use partial masking instead of full masking.
     * @param int $prefixLength Number of visible characters at the start of the string.
     * @param int $suffixLength Number of visible characters at the end of the string.
     * @param string $maskChar Character used for masking.
     * @param int $maxMaskLength Maximum number of mask characters in the middle part.
     */
    public function __construct(
        private array $sensitiveKeys = ['apiKey', 'secretKey', 'signature'],
        private bool $partialMask = true,
        private int $prefixLength = 2,
        private int $suffixLength = 2,
        private string $maskChar = '*',
        private int $maxMaskLength = 4,
    ) {
        $this->normalizedKeys = array_map('strtolower', $this->sensitiveKeys);
    }

    /**
     * Sanitize request array by masking sensitive keys.
     */
    public function sanitize(array $request): array
    {
        foreach ($request as $key => &$value) {
            if (is_array($value)) {
                $value = $this->sanitize($value); // recursive processing
            } elseif ($this->isSensitiveKey($key) && is_scalar($value)) {
                $value = $this->maskValue((string)$value);
            }
        }

        return $request;
    }

    /**
     * Check if a key is sensitive.
     */
    private function isSensitiveKey(string $key): bool
    {
        return in_array(strtolower($key), $this->normalizedKeys, true);
    }

    /**
     * Mask a value (full or partial depending on config).
     */
    private function maskValue(string $value): string
    {
        if ($value === '') {
            return '';
        }
        if (!$this->partialMask) {
            return str_repeat($this->maskChar, strlen($value));
        }
        $len = strlen($value);
        $visible = $this->prefixLength + $this->suffixLength;
        if ($len <= $visible) {
            return str_repeat($this->maskChar, $len);
        }
        $maskedLength = min($len - $visible, $this->maxMaskLength);
        $maskedPart = str_repeat($this->maskChar, $maskedLength);

        return substr($value, 0, $this->prefixLength)
            . $maskedPart
            . substr($value, -$this->suffixLength);
    }
}
