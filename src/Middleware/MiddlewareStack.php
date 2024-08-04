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
 * @internal
 */
final readonly class MiddlewareStack
{
    /**
     * @param iterable<Middleware> $middlewares
     */
    public function __construct(
        private iterable $middlewares,
    ) {
    }

    public function handle(Envelope $envelope): void
    {
        /** @var \Iterator<int, Middleware> $iterator */
        $iterator = (fn (): \Generator => yield from $this->middlewares)();

        if (!$iterator->valid()) {
            return;
        }

        $iterator->current()->handle($envelope, $this->next($iterator));
    }

    private function next(\Iterator $iterator): NextMiddleware
    {
        $iterator->next();

        if ($iterator->valid()) {
            return new SomeMiddleware($iterator->current(), fn () => $this->next($iterator));
        }

        return new NoneMiddleware();
    }
}
