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

use Std\Type\None;
use Std\Type\Option;
use Std\Type\OptionFactory;
use Std\Type\Some;

/**
 * A collection of stamps.
 *
 * @template T of Stamp
 */
final class Stamps implements \Countable
{
    /**
     * @param array<class-string<T>, array<T>> $collection
     */
    private array $collection = [];

    /**
     * @param array<T> $stamps
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
     * @param class-string<T> $class
     */
    public function has(string $class): bool
    {
        return isset($this->collection[$class]);
    }

    /**
     * @param class-string<T> $class
     *
     * @return None|Some<T>
     */
    public function first(string $class): None|Some
    {
        return OptionFactory::from($this->collection[$class][0] ?? null);
    }

    /**
     * @param class-string<T> $class
     *
     * @return None|Some<T>
     */
    public function last(string $class): Option
    {
        if ([] === $stamps = $this->collection[$class] ?? []) {
            return new None();
        }

        return new Some($this->collection[$class][\count($stamps) - 1]);
    }

    /**
     * @template U of Stamp
     *
     * @param class-string<U>   $class
     * @param \Closure(U): bool $fn
     */
    public function filter(string $class, \Closure $fn): self
    {
        $self = clone $this;
        $self->collection[$class] = \array_filter($self->collection[$class] ?? [], $fn);

        return $self;
    }

    /**
     * @template U of Stamp
     *
     * @param class-string<U>    $class
     * @param \Closure(U): mixed $fn
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
