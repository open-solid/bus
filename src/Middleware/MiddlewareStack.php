<?php

namespace Yceruto\Messenger\Middleware;

use Yceruto\Messenger\Model\Envelope;

/**
 * @internal
 */
final readonly class MiddlewareStack
{
    /**
     * @param \Traversable<Middleware>|iterable<Middleware> $middlewares
     */
    public function __construct(private iterable $middlewares)
    {
    }

    public function handle(Envelope $envelope): void
    {
        $next = static fn (): null => null;

        if ($this->middlewares instanceof \Traversable) {
            $middlewares = iterator_to_array($this->middlewares);
        } else {
            $middlewares = $this->middlewares;
        }

        foreach (array_reverse($middlewares) as $middleware) {
            $next = static fn (Envelope $envelope): null => $middleware->handle($envelope, $next);
        }

        $next($envelope);
    }
}
