<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\Websocket;

use Empiriq\BinanceTradeBundle\Common\Clients\Websocket\Dto\PendingRequest;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Binance\BinanceException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Network\DisconnectedException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Network\TimeoutException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\RuntimeException;
use Empiriq\BinanceTradeBundle\Common\Exceptions\Serialization\DeserializationException;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Promise\Timer\TimeoutException as ReactTimeoutException;
use Throwable;

use function React\Promise\Timer\timeout;

abstract class ResponseResolver extends RequestSender
{
    protected float $resolverTimeout = 10;

    /* @var array<string, PendingRequest> */
    private array $pending = [];

    protected function message(array $data): void
    {
        if ($rawResponse = static::extractRawResponse($data)) {
            if ($pending = $this->ejectPending($data['id'])) {
                if (!isset($data['status']) || $data['status'] === 200) {
                    try {
                        $response = $this->serializer->denormalize($rawResponse, $pending->type);
                        $this->logger->info(sprintf('Received response (id: %s)', $pending->id), $data['result'] ?? []);
                        $pending->deferred->resolve($response);
                    } catch (Throwable $e) {
                        $this->logger->warning(
                            sprintf('Response denormalization failed (id: %s): %s', $pending->id, $e->getMessage()),
                            $data
                        );
                        $pending->deferred->reject(new DeserializationException($e->getMessage(), $e->getCode(), $e));
                    }
                } else {
                    $msg = $data['error']['msg'] ?? 'unknown';
                    $code = $data['error']['code'] ?? 0;
                    $this->logger->error(sprintf('Error response (id: %s)', $pending->id), $data);
                    $pending->deferred->reject(new BinanceException($msg, $code, '', $data, []));
                }
            } else {
                $this->logger->warning(sprintf('Pending request not found for the received response'), $data);
            }
        } else {
            parent::message($data);
        }
    }

    protected function addPending(array $request, string $type): PromiseInterface
    {
        $pending = new PendingRequest($request['id'], new Deferred(), $request, $type);
        $this->pending[$pending->id] = $pending;
        $this->logger->info(sprintf('Created pending request (id: %s)', $pending->id), $pending->request);

        return timeout($pending->deferred->promise(), $this->resolverTimeout)
            ->catch(function (ReactTimeoutException $exception) use ($pending) {
                if (!isset($this->pending[$pending->id])) {
                    return;
                }
                $this->logger->warning(sprintf('Request timed out (id: %s)', $pending->id), $pending->request);
                $pending->deferred->reject(new TimeoutException('Request timed out', 0, $exception));
                unset($this->pending[$pending->id]);
                throw $exception;
            });
    }

    private function ejectPending(string $id): ?PendingRequest
    {
        if (isset($this->pending[$id])) {
            $pending = $this->pending[$id];
            unset($this->pending[$id]);

            return $pending;
        }

        return null;
    }

    private function rejectAllPending(RuntimeException $reason): void
    {
        if ($this->pending) {
            $this->logger->warning(
                sprintf('Rejecting all pending requests (%d), reason: %s', count($this->pending), $reason->getMessage())
            );
        }
        foreach ($this->pending as $item) {
            $item->deferred->reject($reason);
        }
        $this->pending = [];
    }

    public function shutdown(): void
    {
        $this->connection->close();
    }

    protected function close(): void
    {
        $this->rejectAllPending(new DisconnectedException());
    }

    protected function error(DisconnectedException $exception): void
    {
        $this->rejectAllPending($exception);
    }

    abstract protected static function extractRawResponse(array $data): ?array;
}
