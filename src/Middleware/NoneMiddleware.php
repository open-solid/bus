<?php

namespace OpenSolid\Messenger\Middleware;

use OpenSolid\Messenger\Model\Envelope;

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
