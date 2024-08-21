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

namespace OpenSolid\Bus\Bridge\Doctrine\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NextMiddleware;

final readonly class DoctrineTransactionMiddleware implements Middleware
{
    public function __construct(
        private ManagerRegistry $registry,
        private ?string $entityManagerName = null,
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $manager = $this->registry->getManager($this->entityManagerName);

        if (!$manager instanceof EntityManagerInterface) {
            throw new \LogicException('Doctrine ORM entity managers are only supported');
        }

        $manager->wrapInTransaction(fn () => $next->handle($envelope));
    }
}
