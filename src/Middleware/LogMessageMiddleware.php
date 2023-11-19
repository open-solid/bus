<?php

namespace Yceruto\Messenger\Middleware;

use Psr\Log\LoggerInterface;
use Yceruto\Messenger\Model\Envelope;

final readonly class LogMessageMiddleware implements Middleware
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Envelope $envelope, callable $next): void
    {
        $this->logger->info('Received message {class}', [
            'class' => get_class($envelope->message),
        ]);

        $next($envelope);
    }
}
