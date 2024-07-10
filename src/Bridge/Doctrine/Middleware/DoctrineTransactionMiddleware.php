<?php

namespace OpenSolid\Messenger\Bridge\Doctrine\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use OpenSolid\Messenger\Middleware\Middleware;
use OpenSolid\Messenger\Middleware\NextMiddleware;
use OpenSolid\Messenger\Model\Envelope;

final readonly class DoctrineTransactionMiddleware implements Middleware
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $this->em->wrapInTransaction(static fn () => $next->handle($envelope));
    }
}
