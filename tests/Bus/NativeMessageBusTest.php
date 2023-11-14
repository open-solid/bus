<?php

namespace Yceruto\Tests\Messenger\Bus;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Handler\HandlersLocator;
use Yceruto\Messenger\Middleware\HandleMessageMiddleware;
use Yceruto\Tests\Messenger\Fixtures\MyMessage;

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
