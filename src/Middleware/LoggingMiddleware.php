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
use Psr\Log\LoggerInterface;

final readonly class LoggingMiddleware implements Middleware
{
    public function __construct(
        private LoggerInterface $logger,
        private string $topic = 'message',
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $this->logger->info(sprintf('Received %s of type {class}', $this->topic), [
            'class' => $envelope->message::class,
        ]);

        $next->handle($envelope);
    }
}
