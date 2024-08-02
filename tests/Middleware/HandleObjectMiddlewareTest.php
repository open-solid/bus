<?php

namespace OpenSolid\Tests\Messenger\Middleware;

use OpenSolid\Messenger\Middleware\NoneMiddleware;
use PHPUnit\Framework\TestCase;
use OpenSolid\Messenger\Error\NoHandlerForObject;
use OpenSolid\Messenger\Error\MultipleHandlersForObject;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Handler\ObjectHandlersLocator;
use OpenSolid\Messenger\Middleware\HandleObjectMiddleware;
use OpenSolid\Messenger\Model\Envelope;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;
use OpenSolid\Tests\Messenger\Fixtures\MyMessageHandler;

class HandleObjectMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $message = new MyMessage();
        $middleware = new HandleObjectMiddleware(new ObjectHandlersLocator([
            MyMessage::class => [new MyMessageHandler()],
        ]));
        $envelop = Envelope::wrap($message);
        $middleware->handle($envelop, new NoneMiddleware());

        $this->assertSame($message, $envelop->result);
    }

    public function testNoHandlerForObject(): void
    {
        $this->expectException(NoHandlerForObject::class);
        $this->expectExceptionMessage('No handler for object of type "OpenSolid\Tests\Messenger\Fixtures\MyMessage".');

        $middleware = new HandleObjectMiddleware(new ObjectHandlersLocator([]), HandlersCountPolicy::SINGLE_HANDLER);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }

    public function testSingleHandlerForObject(): void
    {
        $this->expectException(MultipleHandlersForObject::class);
        $this->expectExceptionMessage('Object of type "OpenSolid\Tests\Messenger\Fixtures\MyMessage" was handled multiple times. Only one handler is expected.');

        $middleware = new HandleObjectMiddleware(new ObjectHandlersLocator([
            MyMessage::class => [
                static fn (MyMessage $message) => $message,
                static fn (MyMessage $message) => $message,
            ],
        ]), HandlersCountPolicy::SINGLE_HANDLER);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }
}
