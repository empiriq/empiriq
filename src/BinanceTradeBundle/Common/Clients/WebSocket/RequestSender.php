<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\WebSocket;

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
use Exception;
use React\Promise\PromiseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerBaseException;
use Throwable;

use function React\Promise\reject;

abstract class RequestSender extends EventDispatcher
{
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
            $params = [];
            if (!is_null($payload)) {
                $params = (array)$this->serializer->normalize($payload);
            }
            if (!is_null($recvWindow)) {
                $params['recvWindow'] = $recvWindow;
            }
            if (!$this->isLoggedIn() && $permission->requiresApiKey()) {
                $params['apiKey'] = $this->config->apiKey;
            }
            if (!$this->isLoggedIn() && $permission->requiresSignature()) {
                $params['timestamp'] = $this->calculateTimestamp();
                $params['signature'] = $this->signer->createSignature($params);
            }
            $request = [
                'id' => bin2hex(random_bytes(8)),
                'method' => $method,
            ];
            if ($params) {
                $request['params'] = $params;
            }
            $this->logger->info(
                sprintf('Sending request (method: %s, id: %s)', $method, $request['id']),
                $this->sanitizer->sanitize($params)
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

            return reject(new RuntimeException($exception->getMessage(), (int)$exception->getCode(), $exception));
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

    /**
     * @throws Exception
     */
    public function calculateTimeOffset(int $serverTime): void
    {
        $this->timeOffsetMs = $serverTime - (int)(new DateTime('now', new DateTimeZone('UTC')))->format('Uv');
    }

    /**
     * @throws Exception
     */
    private function calculateTimestamp(): int
    {
        return (int)(new DateTime('now', new DateTimeZone('UTC')))->format('Uv') + $this->timeOffsetMs;
    }
}
