<?php

namespace OpenSolid\Bus\Envelope\Stamp;

final class Stamps
{
    /**
     * @param array<class-string, array<object>> $collection
     */
    private array $collection = [];

    /**
     * @param array<object> $stamps
     */
    public function __construct(array $stamps)
    {
        foreach ($stamps as $stamp) {
            $this->add($stamp);
        }
    }

    public function add(object $stamp): self
    {
        $this->collection[$stamp::class][] = $stamp;

        return $this;
    }

    /**
     * @param class-string $class
     */
    public function first(string $class): ?object
    {
        return $this->collection[$class][0] ?? null;
    }

    /**
     * @param class-string $class
     */
    public function last(string $class): ?object
    {
        if (!$stamps = $this->collection[$class] ?? []) {
            return null;
        }

        return $this->collection[$class][\count($stamps) - 1];
    }

    /**
     * @param class-string $class
     *
     * @return array<object>
     */
    public function all(string $class): array
    {
        return $this->collection[$class] ?? [];
    }

    public function filter(string $class, \Closure $fn): self
    {
        $self = clone $this;
        $self->collection[$class] = \array_filter($self->collection[$class] ?? [], $fn);

        return $self;
    }

    /**
     * @param class-string            $class
     * @param \Closure(object): mixed $fn
     */
    public function map(string $class, \Closure $fn): array
    {
        return \array_map($fn, $this->collection[$class] ?? []);
    }
}
