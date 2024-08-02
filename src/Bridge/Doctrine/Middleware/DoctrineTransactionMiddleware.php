<?php

namespace OpenSolid\Bus\Bridge\Doctrine\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NextMiddleware;
use OpenSolid\Bus\Model\Envelope;

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
