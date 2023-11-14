<?php

namespace Yceruto\Tests\Messenger\Middleware;

use PHPUnit\Framework\TestCase;
use Yceruto\Messenger\Error\NoHandlerForMessage;
use Yceruto\Messenger\Error\SingleHandlerForMessage;
use Yceruto\Messenger\Handler\HandlersCountPolicy;
use Yceruto\Messenger\Handler\HandlersLocator;
use Yceruto\Messenger\Middleware\HandleMessageMiddleware;
use Yceruto\Messenger\Model\Envelope;
use Yceruto\Tests\Messenger\Fixtures\MyMessage;
use Yceruto\Tests\Messenger\Fixtures\MyMessageHandler;

class HandleMessageMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $message = new MyMessage();
        $handlerMiddleware = new HandleMessageMiddleware(new HandlersLocator([
            MyMessage::class => [new MyMessageHandler()],
        ]));
        $envelop = Envelope::wrap($message);
        $handlerMiddleware->handle($envelop, static fn () => null);

        $this->assertSame($message, $envelop->result);
    }

    public function testNoHandlerForMessage(): void
    {
        $this->expectException(NoHandlerForMessage::class);
        $this->expectExceptionMessage('No handler for message "Yceruto\Tests\Messenger\Fixtures\MyMessage".');

        $handlerMiddleware = new HandleMessageMiddleware(new HandlersLocator([]), HandlersCountPolicy::SINGLE_HANDLER);
        $envelop = Envelope::wrap(new MyMessage());
        $handlerMiddleware->handle($envelop, static fn () => null);
    }

    public function testSingleHandlerForMessage(): void
    {
        $this->expectException(SingleHandlerForMessage::class);
        $this->expectExceptionMessage('Message of type "Yceruto\Tests\Messenger\Fixtures\MyMessage" was handled multiple times. Only one handler is expected.');

        $handlerMiddleware = new HandleMessageMiddleware(new HandlersLocator([
            MyMessage::class => [
                static fn (MyMessage $message) => $message,
                static fn (MyMessage $message) => $message,
            ],
        ]), HandlersCountPolicy::SINGLE_HANDLER);
        $envelop = Envelope::wrap(new MyMessage());
        $handlerMiddleware->handle($envelop, static fn () => null);
    }
}
