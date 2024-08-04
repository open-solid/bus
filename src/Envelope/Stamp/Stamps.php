<?php

namespace OpenSolid\Bus\Envelope\Stamp;

final class Stamps implements \Countable
{
    /**
     * @param array<class-string, array<object>> $collection
     */
    private array $collection = [];

    /**
     * @param array<object> $stamps
     */
    public function __construct(array $stamps = [])
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
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T|null
     */
    public function first(string $class): ?object
    {
        return $this->collection[$class][0] ?? null;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T|null
     */
    public function last(string $class): ?object
    {
        if ([] === $stamps = $this->collection[$class] ?? []) {
            return null;
        }

        return $this->collection[$class][\count($stamps) - 1];
    }

    /**
     * @template T of object
     *
     * @param class-string<T>   $class
     * @param \Closure(T): bool $fn
     */
    public function filter(string $class, \Closure $fn): self
    {
        $self = clone $this;
        $self->collection[$class] = \array_filter($self->collection[$class] ?? [], $fn);

        return $self;
    }

    /**
     * @template T of object
     *
     * @param class-string<T>    $class
     * @param \Closure(T): mixed $fn
     */
    public function map(string $class, \Closure $fn): array
    {
        return \array_map($fn, $this->collection[$class] ?? []);
    }

    public function count(): int
    {
        return \array_reduce(
            $this->collection,
            static fn (int $carry, array $stamps): int => $carry + \count($stamps),
            0,
        );
    }
}
