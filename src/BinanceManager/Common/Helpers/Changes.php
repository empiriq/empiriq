<?php

namespace Empiriq\BinanceManager\Common\Helpers;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class Changes
{
    /**
     * Build array of changes between two entities of the same type.
     *
     * @template T of object
     * @param T $oldEntity
     * @param T $newEntity
     * @return array<string, array{old: mixed, new: mixed}>
     * @throws ReflectionException
     */
    public static function build(object $oldEntity, object $newEntity): array
    {
        if ($oldEntity::class !== $newEntity::class) {
            throw new InvalidArgumentException('Entities must be of the same type');
        }
        $reflection = new ReflectionClass($oldEntity);
        $changes = [];
        foreach ($reflection->getProperties() as $property) {
            $oldValue = $property->getValue($oldEntity);
            $newValue = $property->getValue($newEntity);
            if ($oldValue !== $newValue) {
                $changes[$property->getName()] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }
}
