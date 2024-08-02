<?php

namespace OpenSolid\Bus\Middleware;

use Closure;
use OpenSolid\Bus\Model\Envelope;

/**
 * @internal
 */
final readonly class SomeMiddleware implements NextMiddleware
{
    public function __construct(
        private Middleware $middleware,
        private Closure $next,
    ) {
    }

    public function handle(Envelope $envelope): void
    {
        $this->middleware->handle($envelope, ($this->next)());
    }
}
