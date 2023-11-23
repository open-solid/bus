<?php

namespace OpenSolid\Tests\Messenger\Bus;

use PHPUnit\Framework\TestCase;
use OpenSolid\Messenger\Bus\NativeLazyMessageBus;
use OpenSolid\Messenger\Bus\NativeMessageBus;
use OpenSolid\Messenger\Middleware\Middleware;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;

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
