<?php

namespace Yceruto\Tests\Messenger\Bus;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Bus\NativeLazyMessageBus;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Middleware\Middleware;
use Yceruto\Tests\Messenger\Fixtures\MyMessage;

class NativeLazyMessageBusTest extends TestCase
{
    public function testDispatchLazily(): void
    {
        $handlerMiddleware = $this->createMock(Middleware::class);
        $handlerMiddleware->expects($this->never())->method('handle');

        $lazyBus = new NativeLazyMessageBus(new NativeMessageBus([$handlerMiddleware]));

        $this->assertNull($lazyBus->dispatch(new MyMessage()));
    }

    public function testFlush(): void
    {
        $handlerMiddleware = $this->createMock(Middleware::class);
        $handlerMiddleware->expects($this->once())->method('handle');

        $lazyBus = new NativeLazyMessageBus(new NativeMessageBus([$handlerMiddleware]));

        $lazyBus->dispatch(new MyMessage());
        // code in between...
        $lazyBus->flush();
    }
}
