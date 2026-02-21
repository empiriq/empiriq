<?php

namespace Empiriq\Logging;

use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Monolog\LogRecord;

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
        parent::__construct($this->normalizeLevel($level), $bubble);
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

    private function normalizeLevel(int|string|Level $level): Level
    {
        if ($level instanceof Level) {
            return $level;
        }

        if (is_int($level)) {
            if (!in_array($level, Level::VALUES, true)) {
                throw new \InvalidArgumentException(sprintf('Invalid log level value: %d', $level));
            }

            return Level::from($level);
        }

        return match (strtolower($level)) {
            'debug' => Level::Debug,
            'info' => Level::Info,
            'notice' => Level::Notice,
            'warning' => Level::Warning,
            'error' => Level::Error,
            'critical' => Level::Critical,
            'alert' => Level::Alert,
            'emergency' => Level::Emergency,
            default => throw new \InvalidArgumentException(sprintf('Invalid log level name: %s', $level)),
        };
    }

    #[\Override]
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

    #[\Override]
    public function handleBatch(array $records): void
    {
        foreach ($records as $record) {
            $this->handle($record);
        }
    }
}
