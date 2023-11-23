<?php

namespace OpenSolid\Messenger\Middleware;

use Psr\Log\LoggerInterface;
use OpenSolid\Messenger\Model\Envelope;

final readonly class LogMessageMiddleware implements Middleware
{
    public function __construct(
        private LoggerInterface $logger,
        private string $topic = 'message',
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Envelope $envelope, callable $next): void
    {
        $this->logger->info(sprintf('Received %s {class}', $this->topic), [
            'class' => get_class($envelope->message),
        ]);

        $next($envelope);
    }
}
