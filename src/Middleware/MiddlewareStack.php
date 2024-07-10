<?php

namespace OpenSolid\Messenger\Middleware;

use Generator;
use Iterator;
use OpenSolid\Messenger\Model\Envelope;

/**
 * @internal
 */
final readonly class MiddlewareStack
{
    /**
     * @var Iterator<int, Middleware>
     */
    private Iterator $iterator;

    /**
     * @param iterable<Middleware> $middlewares
     */
    public function __construct(iterable $middlewares)
    {
        $this->iterator = (static fn (): Generator => yield from $middlewares)();
    }

    public function handle(Envelope $envelope): void
    {
        if (!$this->iterator->valid()) {
            return;
        }

        $this->iterator->current()->handle($envelope, $this->next());
    }

    public function next(): NextMiddleware
    {
        $this->iterator->next();

        if (!$this->iterator->valid()) {
            return new NoneMiddleware();
        }

        return new SomeMiddleware($this->iterator->current(), $this);
    }
}
