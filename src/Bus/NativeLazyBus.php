<?php

namespace OpenSolid\Messenger\Bus;

use Symfony\Contracts\Service\ResetInterface;

final class NativeLazyBus implements LazyBus, ResetInterface
{
    private array $objects = [];

    public function __construct(
        private readonly Bus $bus,
    ) {
    }

    public function dispatch(object $object): null
    {
        $this->objects[] = $object;

        return null;
    }

    public function flush(): void
    {
        while ($object = array_shift($this->objects)) {
            $this->bus->dispatch($object);
        }
    }

    public function reset(): void
    {
        $this->objects = [];
    }
}
