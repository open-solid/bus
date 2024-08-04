<?php

declare(strict_types=1);

/*
 * This file is part of Option Type package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Bus\Handler;

use OpenSolid\Bus\Error\NoHandlerForMessage;
use Psr\Container\ContainerInterface;

/**
 * Maps a message class to a list of handlers.
 */
final readonly class MessageHandlersLocator implements ContainerInterface
{
    /**
     * @param array<class-string, iterable<callable>> $handlers
     */
    public function __construct(
        private array $handlers,
    ) {
    }

    /**
     * @return iterable<callable>
     */
    public function get(string $id): iterable
    {
        return $this->handlers[$id] ?? throw NoHandlerForMessage::from($id);
    }

    public function has(string $id): bool
    {
        return isset($this->handlers[$id]);
    }
}
