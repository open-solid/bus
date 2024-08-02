<?php

namespace OpenSolid\Tests\Bus;

use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\NativeLazyMessageBus;
use OpenSolid\Bus\NativeMessageBus;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use PHPUnit\Framework\TestCase;

class NativeLazyMessageBusTest extends TestCase
{
    public function testLazyDispatchingDoNotHandleImmediately(): void
    {
        $middleware = $this->createMock(Middleware::class);
        $middleware->expects($this->never())->method('handle');

        $bus = new NativeLazyMessageBus(new NativeMessageBus([$middleware]));

        $this->assertNull($bus->dispatch(new MyMessage()));
    }

    public function testLazyDispatchingHandleWhenFlush(): void
    {
        $middleware = $this->createMock(Middleware::class);
        $middleware->expects($this->once())->method('handle');

        $bus = new NativeLazyMessageBus(new NativeMessageBus([$middleware]));
        $bus->dispatch(new MyMessage());

        // code in between...

        $bus->flush();
    }
}
