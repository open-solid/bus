<?php

namespace OpenSolid\Messenger\Middleware;

use OpenSolid\Messenger\Model\Envelope;

interface NextMiddleware
{
    public function handle(Envelope $envelope): void;
}
