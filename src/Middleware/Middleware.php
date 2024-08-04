<?php

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;

/**
 * Handles an Envelope message and pass control to the next middleware in the stack.
 */
interface Middleware
{
    public function handle(Envelope $envelope, NextMiddleware $next): void;
}
