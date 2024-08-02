<?php

namespace OpenSolid\Tests\Messenger\Bus;

use PHPUnit\Framework\TestCase;
use OpenSolid\Messenger\Bus\NativeLazyBus;
use OpenSolid\Messenger\Bus\NativeBus;
use OpenSolid\Messenger\Middleware\Middleware;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;

class NativeLazyBusTest extends TestCase
{
    public function testLazyDispatchingDoNotHandleImmediately(): void
    {
        $middleware = $this->createMock(Middleware::class);
        $middleware->expects($this->never())->method('handle');

        $lazyBus = new NativeLazyBus(new NativeBus([$middleware]));

        $this->assertNull($lazyBus->dispatch(new MyMessage()));
    }

    public function testLazyDispatchingHandleWhenFlush(): void
    {
        $middleware = $this->createMock(Middleware::class);
        $middleware->expects($this->once())->method('handle');

        $lazyBus = new NativeLazyBus(new NativeBus([$middleware]));
        $lazyBus->dispatch(new MyMessage());

        // code in between...

        $lazyBus->flush();
    }
}
