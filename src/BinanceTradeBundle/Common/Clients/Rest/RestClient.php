<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\Rest;

use DateTime;
use DateTimeZone;
use Empiriq\BinanceContracts\Common\PermissionInterface;
use Empiriq\BinanceTradeBundle\Common\Configs\RestApiConfig;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Network\DisconnectedException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\RuntimeException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Serialization\SerializationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\Contracts\SerializerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use React\Http\Browser;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerBaseException;
use Throwable;

use function React\Promise\reject;

abstract class RestClient
{
    protected SignerInterface $signer;
    protected SerializerInterface $serializer;
    protected LoggerInterface $logger;
    protected Browser $client;
    protected RestApiConfig $config;
    private int $timeOffsetMs = 0;

    /**
     * @template T of object
     * @param string $method
     * @param string $path
     * @param PermissionInterface $permission
     * @param class-string<T> $type
     * @param mixed|null $payload
     * @param int|null $recvWindow
     *
     * @return PromiseInterface<T>
     * @throws Exception
     */
    public function send(
        string $method,
        string $path,
        PermissionInterface $permission,
        string $type,
        mixed $payload = null,
        ?int $recvWindow = null
    ): PromiseInterface {
        $id = bin2hex(random_bytes(8));
        $headers = [];
        $params = [];
        $body = '';
        try {
            if (!is_null($payload)) {
                $params = (array)$this->serializer->normalize($payload);
            }
            if (!is_null($recvWindow)) {
                $params['recvWindow'] = $recvWindow;
            }
            if ($permission->requiresApiKey()) {
                $headers['X-MBX-APIKEY'] = $this->config->apiKey;
            }
            if ($permission->requiresSignature()) {
                $params['timestamp'] = $this->calculateTimestamp();
                $params['signature'] = $this->signer->createSignature($params);
            }
            if ($method === 'GET') {
                $path .= '?' . http_build_query($params);
            } else {
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
                $body .= http_build_query($params);
            }
            $this->logger->info(sprintf('Sending request (id: %s) %s %s %s', $id, $method, $path, $body));

            return $this->client
                ->withBase($this->config->uri)
                ->withTimeout($this->config->resolverTimeout)
                ->request($method, $path, $headers, $body)
                ->then(function (Response $response) use ($id, $type): mixed {
                    $data = [
                        'id' => $id,
                        'status' => 200,
                        'result' => $this->serializer->decode($response->getBody()->getContents(), JsonEncoder::FORMAT),
                    ];
                    $this->logger->info(sprintf('Received response (id: %s)', $id), $data['result']);

                    return $this->serializer->denormalize($data, $type);
                });
        } catch (ConfigurationException $e) {
            $this->logger->error(
                sprintf('Failed to send request (id: %s query: %s %s) %s', $id, $method, $path, $e->getMessage())
            );

            return reject($e);
        } catch (DisconnectedException $e) {
            $this->logger->error(
                sprintf('Failed to send request (id: %s query: %s %s) %s', $id, $method, $path, $e->getMessage())
            );

            return reject($e);
        } catch (SerializerBaseException $e) {
            $this->logger->error(
                sprintf(
                    'Serialization failed for request (id: %s query: %s %s) %s',
                    $id,
                    $method,
                    $path,
                    $e->getMessage()
                )
            );

            return reject(new SerializationException($e->getMessage(), $e->getCode(), $e));
        } catch (Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Unexpected error while sending request (id: %s query: %s %s) %s',
                    $id,
                    $method,
                    $path,
                    $e->getMessage()
                )
            );

            return reject(new RuntimeException($e->getMessage(), (int)$e->getCode(), $e));
        }
    }

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
