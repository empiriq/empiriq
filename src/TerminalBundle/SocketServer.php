<?php

namespace Empiriq\TerminalBundle;

use Empiriq\Contracts\RunnableInterface;
use React\Promise\PromiseInterface;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer as ReactSocketServer;
use SplObjectStorage;
use Throwable;

use function React\Promise\resolve;

/**
 * @api SocketServer runs a ReactPHP socket server that accepts client connections
 * and forwards them to {@see ClientConnection} for interactive terminal handling.
 */
final class SocketServer implements RunnableInterface
{
    private ?ReactSocketServer $server = null;

    public function __construct(
        private readonly string $uri,
        private readonly array $context,
        private readonly TerminalApplication $terminalApplication,
        private readonly SplObjectStorage $clientConnection = new SplObjectStorage()
    ) {
    }

    /**
     * Start the socket server and listen for connections.
     * @return PromiseInterface<self>
     */
    #[\Override]
    public function run(): PromiseInterface
    {
        $this->server = new ReactSocketServer($this->uri, $this->context);
        $this->server->on('connection', [$this, '__connection']);
        $this->server->on('close', [$this, '__close']);
        $this->server->on('error', [$this, '__error']);

        return resolve($this);
    }

    /**
     * Gracefully shut down the server.
     * @return PromiseInterface<self>
     */
    #[\Override]
    public function shutdown(): PromiseInterface
    {
        $this->server->close();

        return resolve($this);
    }

    #[\Override]
    public function getPriority(): int
    {
        return RunnableInterface::SOCKET_SERVER_PRIORITY;
    }

    /**
     * Handle a new client connection.
     * @param ConnectionInterface $connection Connected client socket
     */
    public function __connection(ConnectionInterface $connection): void
    {
        $this->clientConnection->attach(
            new ClientConnection($connection, $this->clientConnection, $this->terminalApplication)
        );
    }

    /**
     * Handle server close event and resolve the promise.
     */
    public function __close(): void
    {
    }

    /**
     * Handle server error and reject the promise.
     * @param Throwable $e Exception that occurred
     */
    public function __error(Throwable $e): void
    {
    }
}
