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

namespace OpenSolid\Tests\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Envelope\Stamp\HandledStamp;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\MiddlewareStack;
use OpenSolid\Bus\Middleware\NextMiddleware;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use PHPUnit\Framework\TestCase;

class MiddlewareStackTest extends TestCase
{
    public function testHandle(): void
    {
        $middleware1 = new class() implements Middleware {
            public function handle(Envelope $envelope, NextMiddleware $next): void
            {
                $envelope->stamps->add(new HandledStamp('1'));
                $next->handle($envelope);
            }
        };
        $middleware2 = new class() implements Middleware {
            public function handle(Envelope $envelope, NextMiddleware $next): void
            {
                $envelope->stamps->add(new HandledStamp('2'));
                $next->handle($envelope);
            }
        };
        $middleware3 = new class() implements Middleware {
            public function handle(Envelope $envelope, NextMiddleware $next): void
            {
                $envelope->stamps->add(new HandledStamp('3'));
                $next->handle($envelope);
            }
        };
        $stack = new MiddlewareStack([$middleware1, $middleware2, $middleware3]);
        $envelope = Envelope::wrap(new MyMessage());
        $stack->handle($envelope);

        $this->assertSame(['1', '2', '3'], $envelope->unwrap());
    }
}
