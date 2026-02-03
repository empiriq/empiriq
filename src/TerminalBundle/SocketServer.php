<?php

namespace Empiriq\TerminalBundle;

use Empiriq\Contracts\RunnableInterface;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer as ReactSocketServer;
use SplObjectStorage;
use Throwable;

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
     */
    #[\Override]
    public function run(): void
    {
        $this->server = new ReactSocketServer($this->uri, $this->context);
        $this->server->on('connection', [$this, '__connection']);
        $this->server->on('close', [$this, '__close']);
        $this->server->on('error', [$this, '__error']);
    }

    /**
     * Gracefully shut down the server.
     */
    #[\Override]
    public function shutdown(): void
    {
        $this->server->close();
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
