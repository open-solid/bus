<?php

namespace Yceruto\Messenger\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Middleware\HandlerMiddlewareStack;
use Yceruto\Messenger\Middleware\Middleware;
use Yceruto\Messenger\Model\Envelop;
use Yceruto\Messenger\Tests\Fixtures\CreateProduct;

class HandlerMiddlewareStackTest extends TestCase
{
    public function testHandle(): void
    {
        $middleware1 = new class() implements Middleware {
            public function handle(Envelop $envelop, callable $next): void
            {
                $envelop->result = '1';
                $next($envelop);
            }
        };
        $middleware2 = new class() implements Middleware {
            public function handle(Envelop $envelop, callable $next): void
            {
                $envelop->result .= '2';
                $next($envelop);
            }
        };
        $middleware3 = new class() implements Middleware {
            public function handle(Envelop $envelop, callable $next): void
            {
                $envelop->result .= '3';
                $next($envelop);
            }
        };
        $stack = new HandlerMiddlewareStack([$middleware1, $middleware2, $middleware3]);
        $envelop = Envelop::wrap(new CreateProduct());
        $stack->handle($envelop);

        $this->assertSame('123', $envelop->result);
    }
}
