<?php

namespace OpenSolid\Tests\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Error\MultipleHandlersForMessage;
use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\Handler\MessageHandlersCountPolicy;
use OpenSolid\Bus\Handler\MessageHandlersLocator;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\NoneMiddleware;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use OpenSolid\Tests\Bus\Fixtures\MyMessageHandler;
use PHPUnit\Framework\TestCase;

class HandlingMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $message = new MyMessage();
        $middleware = new HandlingMiddleware(new MessageHandlersLocator([
            MyMessage::class => [new MyMessageHandler()],
        ]));
        $envelop = Envelope::wrap($message);
        $middleware->handle($envelop, new NoneMiddleware());

        $this->assertSame($message, $envelop->unwrap());
    }

    public function testNoHandlerForObject(): void
    {
        $this->expectException(NoHandlerForMessage::class);
        $this->expectExceptionMessage('No handler for message of type "OpenSolid\Tests\Bus\Fixtures\MyMessage".');

        $middleware = new HandlingMiddleware(new MessageHandlersLocator([]), MessageHandlersCountPolicy::SINGLE_HANDLER);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }

    public function testSingleHandlerForObject(): void
    {
        $this->expectException(MultipleHandlersForMessage::class);
        $this->expectExceptionMessage('Message of type "OpenSolid\Tests\Bus\Fixtures\MyMessage" was handled multiple times. Only one handler is expected.');

        $middleware = new HandlingMiddleware(new MessageHandlersLocator([
            MyMessage::class => [
                static fn (MyMessage $message) => $message,
                static fn (MyMessage $message) => $message,
            ],
        ]), MessageHandlersCountPolicy::SINGLE_HANDLER);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }
}
