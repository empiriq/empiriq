<?php

namespace Empiriq\Logging;

use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Monolog\LogRecord;
use Psr\Log\LogLevel;

/**
 * Routes log records to different handlers based on custom predicates.
 *
 * @api
 */
final class RecordRoutingHandler extends AbstractHandler
{
    /**
     * @var array<int, array{when: callable(LogRecord): bool, handler: HandlerInterface}>
     */
    private array $routes;

    public function __construct(
        array $routes,
        private readonly ?HandlerInterface $fallback = null,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
        foreach ($routes as $route) {
            if (!isset($route['when'], $route['handler'])) {
                throw new \InvalidArgumentException('Each route must define "when" and "handler".');
            }
            if (!is_callable($route['when'])) {
                throw new \InvalidArgumentException('Route "when" must be callable.');
            }
            if (!$route['handler'] instanceof HandlerInterface) {
                throw new \InvalidArgumentException('Route "handler" must implement HandlerInterface.');
            }
        }
        $this->routes = $routes;
    }

    public function handle(LogRecord $record): bool
    {
        if (!$this->isHandling($record)) {
            return false;
        }

        foreach ($this->routes as $route) {
            if (($route['when'])($record)) {
                $route['handler']->handle($record);
                return !$this->bubble;
            }
        }

        if ($this->fallback) {
            $this->fallback->handle($record);
            return !$this->bubble;
        }

        return false;
    }

    public function handleBatch(array $records): void
    {
        foreach ($records as $record) {
            $this->handle($record);
        }
    }
}
