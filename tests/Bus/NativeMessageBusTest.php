<?php

namespace Yceruto\Messenger\Tests\Bus;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Middleware\HandlerMiddlewareStack;
use Yceruto\Messenger\Middleware\Middleware;
use Yceruto\Messenger\Model\Envelop;
use Yceruto\Messenger\Tests\Fixtures\CreateProduct;

class NativeMessageBusTest extends TestCase
{
    public function testDispatch(): void
    {
        $handlerMiddleware = new class() implements Middleware {
            public function handle(Envelop $envelop, callable $next): void {
                $envelop->result = $envelop->message;
                $next($envelop);
            }
        };
        $bus = new NativeMessageBus(new HandlerMiddlewareStack([$handlerMiddleware]));
        $message = new CreateProduct();

        $this->assertSame($message, $bus->dispatch($message));
    }
}
