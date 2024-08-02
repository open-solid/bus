<?php

namespace OpenSolid\Messenger\Middleware;

use Psr\Log\LoggerInterface;
use OpenSolid\Messenger\Model\Envelope;

final readonly class LogObjectHandlingMiddleware implements Middleware
{
    public function __construct(
        private LoggerInterface $logger,
        private string $topic = 'object',
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $this->logger->info(sprintf('Received %s of type {class}', $this->topic), [
            'class' => $envelope->object::class,
        ]);

        $next->handle($envelope);
    }
}
