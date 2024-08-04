<?php

namespace OpenSolid\Bus\Middleware;

use Generator;
use Iterator;
use OpenSolid\Bus\Envelope\Envelope;

/**
 * @internal
 */
final readonly class MiddlewareStack
{
    /**
     * @param iterable<Middleware> $middlewares
     */
    public function __construct(
        private iterable $middlewares,
    ) {
    }

    public function handle(Envelope $envelope): void
    {
        /** @var Iterator<int, Middleware> $iterator */
        $iterator = (fn (): Generator => yield from $this->middlewares)();

        if (!$iterator->valid()) {
            return;
        }

        $iterator->current()->handle($envelope, $this->next($iterator));
    }

    private function next(Iterator $iterator): NextMiddleware
    {
        $iterator->next();

        if ($iterator->valid()) {
            return new SomeMiddleware($iterator->current(), fn () => $this->next($iterator));
        }

        return new NoneMiddleware();
    }
}
