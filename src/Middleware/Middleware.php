<?php

namespace Yceruto\Messenger\Middleware;

use Yceruto\Messenger\Model\Envelop;

/**
 * Handles an Envelope object and pass control to the next middleware in the stack.
 */
interface Middleware
{
    /**
     * @param callable(Envelop): void $next
     */
    public function handle(Envelop $envelop, callable $next): void;
}
