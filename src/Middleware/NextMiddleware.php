<?php

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Model\Envelope;

interface NextMiddleware
{
    public function handle(Envelope $envelope): void;
}
