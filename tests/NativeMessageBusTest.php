<?php

namespace OpenSolid\Tests\Bus;

use OpenSolid\Bus\Handler\MessageHandlersLocator;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\NativeMessageBus;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use PHPUnit\Framework\TestCase;

class NativeMessageBusTest extends TestCase
{
    public function testDispatch(): void
    {
        $handler = static fn(MyMessage $message): MyMessage => $message;
        $bus = new NativeMessageBus([
            new HandlingMiddleware(new MessageHandlersLocator([
                MyMessage::class => [$handler],
            ])),
        ]);
        $message = new MyMessage();

        $this->assertSame($message, $bus->dispatch($message));
    }
}
