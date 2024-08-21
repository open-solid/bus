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

namespace OpenSolid\Bus\Decorator;

use Psr\Container\ContainerInterface;

/**
 * Maps a handler class to a list of decorators.
 */
final readonly class DecoratorsLocator implements ContainerInterface
{
    /**
     * @param array<class-string, iterable<Decorator>> $decorators
     */
    public function __construct(
        private array $decorators,
    ) {
    }

    /**
     * @return iterable<Decorator>
     */
    public function get(string $id): iterable
    {
        return $this->decorators[$id] ?? [];
    }

    public function has(string $id): bool
    {
        return isset($this->decorators[$id]);
    }
}
