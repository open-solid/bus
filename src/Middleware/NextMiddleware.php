<?php

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;

interface NextMiddleware
{
    public function handle(Envelope $envelope): void;
}
