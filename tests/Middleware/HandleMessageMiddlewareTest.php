<?php

namespace OpenSolid\Tests\Messenger\Middleware;

use OpenSolid\Messenger\Middleware\NoneMiddleware;
use PHPUnit\Framework\TestCase;
use OpenSolid\Messenger\Error\NoHandlerForMessage;
use OpenSolid\Messenger\Error\MultipleHandlersForMessage;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Handler\HandlersLocator;
use OpenSolid\Messenger\Middleware\HandleMessageMiddleware;
use OpenSolid\Messenger\Model\Envelope;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;
use OpenSolid\Tests\Messenger\Fixtures\MyMessageHandler;

class HandleMessageMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $message = new MyMessage();
        $handlerMiddleware = new HandleMessageMiddleware(new HandlersLocator([
            MyMessage::class => [new MyMessageHandler()],
        ]));
        $envelop = Envelope::wrap($message);
        $handlerMiddleware->handle($envelop, new NoneMiddleware());

        $this->assertSame($message, $envelop->result);
    }

    public function testNoHandlerForMessage(): void
    {
        $this->expectException(NoHandlerForMessage::class);
        $this->expectExceptionMessage('No handler for message "OpenSolid\Tests\Messenger\Fixtures\MyMessage".');

        $handlerMiddleware = new HandleMessageMiddleware(new HandlersLocator([]), HandlersCountPolicy::SINGLE_HANDLER);
        $envelop = Envelope::wrap(new MyMessage());
        $handlerMiddleware->handle($envelop, new NoneMiddleware());
    }

    public function testSingleHandlerForMessage(): void
    {
        $this->expectException(MultipleHandlersForMessage::class);
        $this->expectExceptionMessage('Message of type "OpenSolid\Tests\Messenger\Fixtures\MyMessage" was handled multiple times. Only one handler is expected.');

        $handlerMiddleware = new HandleMessageMiddleware(new HandlersLocator([
            MyMessage::class => [
                static fn (MyMessage $message) => $message,
                static fn (MyMessage $message) => $message,
            ],
        ]), HandlersCountPolicy::SINGLE_HANDLER);
        $envelop = Envelope::wrap(new MyMessage());
        $handlerMiddleware->handle($envelop, new NoneMiddleware());
    }
}
