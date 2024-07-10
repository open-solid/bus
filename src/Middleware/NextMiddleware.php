<?php

namespace OpenSolid\Messenger\Middleware;

use OpenSolid\Messenger\Model\Envelope;

/**
 * @internal
 */
interface NextMiddleware
{
    public function handle(Envelope $envelope): void;
}
