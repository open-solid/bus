<?php

declare(strict_types=1);

/*
 * This file is part of Option Type package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;

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
