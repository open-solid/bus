<?php

declare(strict_types=1);

/*
 * This file is part of OpenSolid package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Bus\Envelope\Stamp;

/**
 * A collection of stamps.
 */
final class Stamps implements \Countable
{
    /**
     * @param array<class-string, array<Stamp>> $collection
     */
    private array $collection = [];

    /**
     * @param array<Stamp> $stamps
     */
    public function __construct(array $stamps = [])
    {
        foreach ($stamps as $stamp) {
            $this->add($stamp);
        }
    }

    public function add(Stamp $stamp): self
    {
        $this->collection[$stamp::class][] = $stamp;

        return $this;
    }

    /**
     * @template T of Stamp
     *
     * @param class-string<T> $class
     *
     * @return T|null
     */
    public function first(string $class): ?Stamp
    {
        return $this->collection[$class][0] ?? null;
    }

    /**
     * @template T of Stamp
     *
     * @param class-string<T> $class
     *
     * @return T|null
     */
    public function last(string $class): ?Stamp
    {
        if ([] === $stamps = $this->collection[$class] ?? []) {
            return null;
        }

        return $this->collection[$class][\count($stamps) - 1];
    }

    /**
     * @template T of Stamp
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
     * @template T of Stamp
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
