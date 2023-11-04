<?php

namespace Yceruto\Messenger\Middleware;

use Psr\Container\ContainerInterface;
use Yceruto\Messenger\Error\HandlerNotFound;
use Yceruto\Messenger\Model\Envelop;

/**
 * Handles a message with a handler.
 */
final readonly class HandlerMiddleware implements Middleware
{
    public function __construct(private ContainerInterface $handlers)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Envelop $envelop, callable $next): void
    {
        $class = get_class($envelop->message);

        if (!$this->handlers->has($class)) {
            throw HandlerNotFound::from($class);
        }

        $handler = $this->handlers->get($class);
        $envelop->result = $handler($envelop->message);

        $next($envelop);
    }
}
