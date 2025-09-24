<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\Websocket;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Network\ConnectionFailedException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Network\DisconnectedException;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\Promise\PromiseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Throwable;

use function Ratchet\Client\connect;
use function React\Promise\reject;

abstract class Connection
{
    protected string $uri;
    protected SerializerInterface $serializer;
    protected LoggerInterface $logger;
    protected ?WebSocket $connection = null;

    public function run(): PromiseInterface
    {
        var_dump(3);
        return connect($this->uri)->then(function (WebSocket $connection) {
            $this->logger->info(sprintf('WebSocket connected (uri: %s)', $this->uri));
            $connection->on('message', [$this, '__message']);
            $connection->on('close', [$this, '__close']);
            $connection->on('error', [$this, '__error']);
            $this->connection = $connection;
            return $this;
        })->catch(function (Throwable $exception) {
            //vendor/react/socket/src/TimeoutConnector.php:60
            $this->logger->error(
                sprintf('WebSocket connection failed (uri: %s, exception: %s)', $this->uri, $exception->getMessage())
            );
            return reject(new ConnectionFailedException($exception->getMessage(), $exception->getCode(), $exception));
        });
    }

    public function shutdown(): void
    {
        $this->logger->info(sprintf('WebSocket disconnect requested (uri: %s)', $this->uri));
        $this->connection?->close();
    }

    protected function getConnection(): WebSocket
    {
        if (is_null($this->connection)) {
            throw new DisconnectedException('No active connection');
        }
        return $this->connection;
    }

    /**
     * @internal not public
     */
    public function __message(MessageInterface $message): void
    {
        $payload = $message->getPayload();
        try {
            $data = $this->serializer->decode($payload, JsonEncoder::FORMAT);
            if (is_array($data)) {
                $this->logger->debug(
                    sprintf('WebSocket message received (uri: %s, payload: %s)', $this->uri, $payload)
                );
                $this->message($data);
            } else {
                $this->logger->warning(
                    sprintf('WebSocket decode failed (uri: %s, reason: non-array data)', $this->uri)
                );
            }
        } catch (Throwable $exception) {
            $this->logger->warning(
                sprintf('WebSocket decode failed (uri: %s, exception: %s)', $this->uri, $exception->getMessage())
            );
        }
    }

    /**
     * @internal not public
     */
    public function __close(): void
    {
        $this->connection = null;
        $this->logger->info(sprintf('WebSocket closed (uri: %s)', $this->uri));
        $this->close();
    }

    /**
     * @internal not public
     */
    public function __error(Throwable $exception): void
    {
        $this->connection = null;
        $this->logger->error(
            sprintf('WebSocket error (uri: %s, exception: %s)', $this->uri, $exception->getMessage())
        );
        $this->error(new DisconnectedException($exception->getMessage(), $exception->getCode(), $exception));
    }

    abstract protected function message(array $data): void;

    abstract protected function close(): void;

    abstract protected function error(DisconnectedException $exception): void;
}
