<?php

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;

/**
 * @internal
 */
final readonly class NoneMiddleware implements NextMiddleware
{
    public function handle(Envelope $envelope): void
    {
        // no-op
    }
}
