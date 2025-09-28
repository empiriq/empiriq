<?php

namespace Empiriq\TerminalBundle;

use React\Socket\ConnectionInterface;
use SplObjectStorage;
use Symfony\Component\Console\Input\StringInput;
use Throwable;

final readonly class ClientConnection
{
    /**
     * @param ConnectionInterface $connection
     * @param SplObjectStorage $clientConnections
     * @param TerminalApplication $terminalApplication
     */
    public function __construct(
        private ConnectionInterface $connection,
        private SplObjectStorage $clientConnections,
        private TerminalApplication $terminalApplication,
    ) {
        $this->connection->on('data', [$this, '__data']);
        $this->connection->on('end', [$this, '__end']);
        $this->connection->on('close', [$this, '__close']);
        $this->connection->on('error', [$this, '__error']);
        $this->connection->write("Welcome to Empiriq Terminal!\n# ");
    }

    /**
     * Пришли данные
     * @param string $data
     * @return void
     */
    public function __data(string $data): void
    {
        $commandLine = trim($data);
        if ($commandLine === '') {
            $this->connection->write('# ');
            return;
        }
        $input = new StringInput($commandLine);
        $stream = new StreamOutput($this->connection);
        try {
            $this->terminalApplication->run($input, $stream);
        } catch (Throwable $e) {
            $this->connection->write("Error: " . $e->getMessage() . "\n");
        }
        $this->connection->write('# ');
    }

    /**
     * Клиент закрыл соединение (корректно)
     * @return void
     */
    public function __end(): void
    {
        $this->connection->write('Bay');
    }

    /**
     * Соединение полностью закрыто (и корректно, и при обрыве)
     * @return void
     */
    public function __close(): void
    {
        $this->connection->removeListener('data', [$this, '__data']);
        $this->connection->removeListener('close', [$this, '__close']);
        $this->clientConnections->detach($this);
    }

    /**
     * Ошибка соединения
     * @param Throwable $data
     * @return void
     */
    public function __error(Throwable $data): void
    {
        $this->connection->write('error: ' . $data->getMessage());
    }
}
