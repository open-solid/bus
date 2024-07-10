<?php

namespace OpenSolid\Messenger\Middleware;

use OpenSolid\Messenger\Model\Envelope;

/**
 * @internal
 */
final readonly class SomeMiddleware implements NextMiddleware
{
    public function __construct(
        private Middleware $middleware,
        private MiddlewareStack $stack,
    ) {
    }

    public function handle(Envelope $envelope): void
    {
        $this->middleware->handle($envelope, $this->stack->next());
    }
}
