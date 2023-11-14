<?php

namespace Yceruto\Tests\Messenger\Middleware;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Middleware\MiddlewareStack;
use Yceruto\Messenger\Middleware\Middleware;
use Yceruto\Messenger\Model\Envelope;
use Yceruto\Tests\Messenger\Fixtures\MyMessage;

class MiddlewareStackTest extends TestCase
{
    public function testHandle(): void
    {
        $middleware1 = new class() implements Middleware {
            public function handle(Envelope $envelope, callable $next): void
            {
                $envelope->result = '1';
                $next($envelope);
            }
        };
        $middleware2 = new class() implements Middleware {
            public function handle(Envelope $envelope, callable $next): void
            {
                $envelope->result .= '2';
                $next($envelope);
            }
        };
        $middleware3 = new class() implements Middleware {
            public function handle(Envelope $envelope, callable $next): void
            {
                $envelope->result .= '3';
                $next($envelope);
            }
        };
        $stack = new MiddlewareStack([$middleware1, $middleware2, $middleware3]);
        $envelope = Envelope::wrap(new MyMessage());
        $stack->handle($envelope);

        $this->assertSame('123', $envelope->result);
    }
}
