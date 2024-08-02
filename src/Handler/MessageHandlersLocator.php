<?php

namespace OpenSolid\Bus\Handler;

use Psr\Container\ContainerInterface;
use OpenSolid\Bus\Error\NoHandlerForMessage;

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
