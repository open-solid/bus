<?php

declare(strict_types=1);

/*
 * This file is part of OpenSolid package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;

/**
 * Handles an Envelope message and pass control to the next middleware in the stack.
 */
interface Middleware
{
    public function handle(Envelope $envelope, NextMiddleware $next): void;
}
