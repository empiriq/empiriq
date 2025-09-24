<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\Websocket;

use DateTime;
use DateTimeZone;
use Empiriq\BinanceContracts\Common\PermissionInterface;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Network\DisconnectedException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\RuntimeException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Serialization\SerializationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceTradeBundle\Common\Signers\Ed25519Signer;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use React\Promise\PromiseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerBaseException;
use Throwable;

use function React\Promise\reject;

abstract class RequestSender extends EventDispatcher
{
    protected string $apiKey = '';
    protected SignerInterface $signer;
    protected SanitizerInterface $sanitizer;
    private bool $isLoggedIn = false;
    private int $timeOffsetMs = 0;

    /**
     * @template T of object
     * @param string $method
     * @param PermissionInterface $permission
     * @param class-string<T> $type
     * @param mixed|null $payload
     * @param int|null $recvWindow
     *
     * @return PromiseInterface<T>
     */
    public function send(
        string $method,
        PermissionInterface $permission,
        string $type,
        mixed $payload = null,
        ?int $recvWindow = null
    ): PromiseInterface {
        try {
            $request = [
                'id' => bin2hex(random_bytes(8)),
                'method' => $method,
            ];
            if (!is_null($payload)) {
                $request['params'] = $this->serializer->normalize($payload);
            }
            if (!is_null($recvWindow)) {
                $request['params']['recvWindow'] = $recvWindow;
            }
            if (!$this->isLoggedIn() && $permission->requiresApiKey()) {
                $request['params']['apiKey'] = $this->getApiKey();
            }
            if (!$this->isLoggedIn() && $permission->requiresSignature()) {
                $request['params']['timestamp'] = $this->calculateTimestamp();
                $request['params']['signature'] = $this->getSigner()->createSignature($request['params']);
            }
            $this->logger->info(
                sprintf('Sending request (method: %s, id: %s)', $method, $request['id']),
                $this->sanitizer->sanitize($request['params'] ?? [])
            );
            $this->getConnection()->send($this->serializer->encode($request, JsonEncoder::FORMAT));

            return $this->addPending($request, $type);
        } catch (ConfigurationException $exception) {
            $this->logger->error(
                sprintf('Failed to send request (method: %s): %s', $method, $exception->getMessage())
            );
            return reject($exception);
        } catch (DisconnectedException $exception) {
            $this->logger->error(
                sprintf('Failed to send request (method: %s): %s', $method, $exception->getMessage())
            );
            return reject($exception);
        } catch (SerializerBaseException $exception) {
            $this->logger->error(
                sprintf('Serialization failed for request (method: %s): %s', $method, $exception->getMessage())
            );
            return reject(new SerializationException($exception->getMessage(), $exception->getCode(), $exception));
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Unexpected error while sending request (method: %s): %s', $method, $exception->getMessage())
            );
            return reject(new RuntimeException($exception->getMessage(), $exception->getCode(), $exception));
        }
    }

    public function canLogIn(): bool
    {
        return $this->signer instanceof Ed25519Signer;
    }

    public function setLoggedIn(bool $value): void
    {
        $this->isLoggedIn = $value;
    }

    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    public function calculateTimeOffset(int $serverTime): void
    {
        $this->timeOffsetMs = $serverTime - (int)(new DateTime('now', new DateTimeZone('UTC')))->format('Uv');
    }

    private function calculateTimestamp(): int
    {
        return (int)(new DateTime('now', new DateTimeZone('UTC')))->format('Uv') + $this->timeOffsetMs;
    }

    private function getSigner(): SignerInterface
    {
        return $this->signer ??= new NullSigner();
    }

    private function getApiKey(): string
    {
        if (!$this->apiKey) {
            throw new ConfigurationException('apiKey required');
        }
        return $this->apiKey;
    }
}
