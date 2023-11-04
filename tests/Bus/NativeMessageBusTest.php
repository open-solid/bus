<?php

namespace Yceruto\Messenger\Tests\Bus;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Bus\MessageBusFactory;
use Yceruto\Messenger\Tests\Fixtures\CreateProduct;

class NativeMessageBusTest extends TestCase
{
    public function testDispatch(): void
    {
        $bus = MessageBusFactory::fromHandlers([
            CreateProduct::class => fn (CreateProduct $message) => $message,
        ]);
        $message = new CreateProduct();

        $this->assertSame($message, $bus->dispatch($message));
    }
}
