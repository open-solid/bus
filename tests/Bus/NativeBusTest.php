<?php

namespace OpenSolid\Tests\Messenger\Bus;

use PHPUnit\Framework\TestCase;
use OpenSolid\Messenger\Bus\NativeBus;
use OpenSolid\Messenger\Handler\ObjectHandlersLocator;
use OpenSolid\Messenger\Middleware\HandleObjectMiddleware;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;

class NativeBusTest extends TestCase
{
    public function testDispatch(): void
    {
        $handler = static fn(MyMessage $message): MyMessage => $message;
        $bus = new NativeBus([
            new HandleObjectMiddleware(new ObjectHandlersLocator([
                MyMessage::class => [$handler],
            ])),
        ]);
        $message = new MyMessage();

        $this->assertSame($message, $bus->dispatch($message));
    }
}
