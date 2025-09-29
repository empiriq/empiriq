<?php

namespace Empiriq\TerminalBundle;

use Empiriq\Contracts\RunnableInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer as ReactSocketServer;
use SplObjectStorage;
use Symfony\Component\Console\Command\Command;
use Throwable;

use function React\Promise\resolve;

/**
 * @api SocketServer runs a ReactPHP socket server that accepts client connections
 * and forwards them to {@see ClientConnection} for interactive terminal handling.
 */
final class SocketServer implements RunnableInterface
{
    private ?ReactSocketServer $socketServer = null;

    public function __construct(
        private readonly string $serverUri,
        private readonly TerminalApplication $terminalApplication,
        private readonly SplObjectStorage $clientConnection = new SplObjectStorage(),
        private readonly Deferred $deferred = new Deferred()
    ) {
    }

    /**
     * Start the socket server and listen for connections.
     * @return PromiseInterface<int> Promise resolving when the server stops.
     */
    #[\Override]
    public function run(): PromiseInterface
    {
        $this->socketServer = new ReactSocketServer($this->serverUri, []);
        $this->socketServer->on('connection', [$this, '__connection']);
        $this->socketServer->on('close', [$this, '__close']);
        $this->socketServer->on('error', [$this, '__error']);

        return $this->deferred->promise();
    }

    /**
     * Gracefully shut down the server.
     * @return PromiseInterface<self>
     */
    #[\Override]
    public function shutdown(): PromiseInterface
    {
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
        $this->deferred->resolve(Command::SUCCESS);
    }

    /**
     * Handle server error and reject the promise.
     * @param Throwable $e Exception that occurred
     */
    public function __error(Throwable $e): void
    {
        $this->deferred->reject($e);
    }
}
