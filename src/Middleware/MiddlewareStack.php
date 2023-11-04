<?php

namespace Yceruto\Messenger\Middleware;

use Yceruto\Messenger\Model\Envelop;

/**
 * Handles a stack of middlewares.
 */
interface MiddlewareStack
{
    public function handle(Envelop $envelop): void;
}
