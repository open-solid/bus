<?php

namespace Yceruto\Messenger\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Error\HandlerNotFound;
use Yceruto\Messenger\Handler\HandlersLocator;
use Yceruto\Messenger\Middleware\HandlerMiddleware;
use Yceruto\Messenger\Model\Envelop;
use Yceruto\Messenger\Tests\Fixtures\CreateProduct;
use Yceruto\Messenger\Tests\Fixtures\MessageWithoutHandler;

class HandlerMiddlewareTest extends TestCase
{
    private HandlerMiddleware $handlerMiddleware;

    protected function setUp(): void
    {
        $this->handlerMiddleware = new HandlerMiddleware(new HandlersLocator([
            CreateProduct::class => static fn (CreateProduct $message) => $message,
        ]));
    }

    public function testHandle(): void
    {
        $message = new CreateProduct();
        $envelop = Envelop::wrap($message);
        $this->handlerMiddleware->handle($envelop, static fn () => null);

        $this->assertSame($message, $envelop->result);
    }

    public function testNotHandlerFound(): void
    {
        $this->expectException(HandlerNotFound::class);
        $this->expectExceptionMessage('Handler not found for message "Yceruto\Messenger\Tests\Fixtures\MessageWithoutHandler"');

        $envelop = Envelop::wrap(new MessageWithoutHandler());
        $this->handlerMiddleware->handle($envelop, static fn () => null);
    }
}
