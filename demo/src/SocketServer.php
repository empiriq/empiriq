<?php

namespace Empiriq\Demo;

use Empiriq\Contracts\RunnableInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer as ReactSocketServer;
use SplObjectStorage;
use Symfony\Component\Console\Command\Command;
use Throwable;

use function React\Promise\resolve;

final class SocketServer implements RunnableInterface
{
    private ?SplObjectStorage $clientConnection = null;
    private ?Deferred $deferred = null;
    private ?ReactSocketServer $socketServer = null;

    public function __construct(
        private readonly TerminalApplication $terminalApplication,
    ) {
    }

    #[\Override]
    public function run(): PromiseInterface
    {
        $this->clientConnection = new SplObjectStorage();
        $this->deferred = new Deferred();
        $this->socketServer = new ReactSocketServer('0.0.0.0:2009', []);
        $this->socketServer->on('connection', [$this, '__connection']);
        $this->socketServer->on('close', [$this, '__close']);
        $this->socketServer->on('error', [$this, '__error']);

        return $this->deferred->promise();
    }

    /**
     * Когда к серверу подключился новый клиент
     * @param ConnectionInterface $connection
     * @return void
     */
    public function __connection(ConnectionInterface $connection): void
    {
        $this->clientConnection->attach(
            new ClientConnection($connection, $this->clientConnection, $this->terminalApplication)
        );
    }

    /**
     * Когда сервер закрыт
     * @return void
     */
    public function __close(): void
    {
        $this->deferred->resolve(Command::SUCCESS);
    }

    /**
     * Когда произошла ошибка
     * @param Throwable $e
     * @return void
     */
    public function __error(Throwable $e): void
    {
        $this->deferred->reject($e);
    }

    public function shutdown(): PromiseInterface
    {
        return resolve($this);
    }
}
