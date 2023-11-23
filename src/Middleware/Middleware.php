<?php

namespace OpenSolid\Messenger\Middleware;

use OpenSolid\Messenger\Model\Envelope;

/**
 * Handles an Envelope object and pass control to the next middleware in the stack.
 */
interface Middleware
{
    /**
     * @param callable(Envelope): void $next
     */
    public function handle(Envelope $envelope, callable $next): void;
}
