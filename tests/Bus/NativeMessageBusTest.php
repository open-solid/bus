<?php

namespace OpenSolid\Tests\Messenger\Bus;

use PHPUnit\Framework\TestCase;
use OpenSolid\Messenger\Bus\NativeMessageBus;
use OpenSolid\Messenger\Handler\HandlersLocator;
use OpenSolid\Messenger\Middleware\HandleMessageMiddleware;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;

class NativeMessageBusTest extends TestCase
{
    public function testDispatch(): void
    {
        $handler = static fn(MyMessage $message): MyMessage => $message;
        $bus = new NativeMessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                MyMessage::class => [$handler],
            ])),
        ]);
        $message = new MyMessage();

        $this->assertSame($message, $bus->dispatch($message));
    }
}
