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

        $result = $bus->dispatch(new MyMessage());

        $this->assertNull($result);
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
