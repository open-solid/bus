<?php

namespace Yceruto\Messenger\Middleware;

use Yceruto\Messenger\Model\Envelop;

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

    public function handle(Envelop $envelop): void
    {
        $next = static fn (): null => null;

        if ($this->middlewares instanceof \Traversable) {
            $middlewares = iterator_to_array($this->middlewares);
        } else {
            $middlewares = $this->middlewares;
        }

        foreach (array_reverse($middlewares) as $middleware) {
            $next = static fn (Envelop $envelop): null => $middleware->handle($envelop, $next);
        }

        $next($envelop);
    }
}
