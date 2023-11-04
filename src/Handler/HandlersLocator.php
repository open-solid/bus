<?php

namespace Yceruto\Messenger\Handler;

use Psr\Container\ContainerInterface;
use Yceruto\Messenger\Error\HandlerNotFound;

final readonly class HandlersLocator implements ContainerInterface
{
    /**
     * @param array<class-string, callable> $handlers
     */
    public function __construct(private array $handlers)
    {
    }

    public function get(string $id): callable
    {
        return $this->handlers[$id] ?? throw HandlerNotFound::from($id);
    }

    public function has(string $id): bool
    {
        return isset($this->handlers[$id]);
    }
}
