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

namespace OpenSolid\Bus\Bridge\Doctrine\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NextMiddleware;

final readonly class DoctrineTransactionMiddleware implements Middleware
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $this->em->wrapInTransaction(fn () => $next->handle($envelope));
    }
}
