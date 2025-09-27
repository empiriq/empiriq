<?php

namespace Empiriq\Demo;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleSignalEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class ConsoleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'onCommand',
            ConsoleEvents::TERMINATE => 'onTerminate',
            ConsoleEvents::ERROR => 'onError',
            ConsoleEvents::SIGNAL => 'onSignal',

        ];
    }

    public function onCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand()->getName();
        echo "Запуск команды: $command\n";
    }

    public function onTerminate(): void
    {
        echo "Команда завершена\n";
    }

    public function onError(): void
    {
        echo "Ошибка в команде!\n";
    }

    public function onSignal($event): void
    {
        var_dump($event);
    }
}
