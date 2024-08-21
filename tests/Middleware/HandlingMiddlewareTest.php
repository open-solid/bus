<?php

declare(strict_types=1);

/*
 * This file is part of OpenSolid package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Tests\Bus\Middleware;

use OpenSolid\Bus\Decorator\DecoratorsLocator;
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Error\MultipleHandlersForMessage;
use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\Handler\HandlersCountPolicy;
use OpenSolid\Bus\Handler\HandlersLocator;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\NoneMiddleware;
use OpenSolid\Tests\Bus\Fixtures\DummyDecorator;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use OpenSolid\Tests\Bus\Fixtures\MyMessageHandler;
use PHPUnit\Framework\TestCase;

class HandlingMiddlewareTest extends TestCase
{
    public function testHandleMessage(): void
    {
        $message = new MyMessage();
        $handlers = [
            MyMessage::class => [new MyMessageHandler()],
        ];
        $middleware = new HandlingMiddleware(new HandlersLocator($handlers));
        $envelop = Envelope::wrap($message);
        $middleware->handle($envelop, new NoneMiddleware());

        $result = $envelop->unwrap();

        $this->assertSame($message, $result);
    }

    public function testNoHandlerForMessage(): void
    {
        $this->expectException(NoHandlerForMessage::class);
        $this->expectExceptionMessage('No handler for message of type "OpenSolid\Tests\Bus\Fixtures\MyMessage".');

        $middleware = new HandlingMiddleware(new HandlersLocator([]), policy: HandlersCountPolicy::SINGLE_HANDLER);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }

    public function testSingleHandlerForMessage(): void
    {
        $this->expectException(MultipleHandlersForMessage::class);
        $this->expectExceptionMessage('Message of type "OpenSolid\Tests\Bus\Fixtures\MyMessage" was handled multiple times. Only one handler is expected.');

        $handlers = [
            MyMessage::class => [
                static fn (MyMessage $message): MyMessage => $message,
                static fn (MyMessage $message): MyMessage => $message,
            ],
        ];
        $middleware = new HandlingMiddleware(new HandlersLocator($handlers), policy: HandlersCountPolicy::SINGLE_HANDLER);

        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }

    public function testHandleMessageWithDecorator(): void
    {
        $message = new MyMessage();
        $handlers = [
            MyMessage::class => [new MyMessageHandler()],
        ];
        $decorators = [
            MyMessageHandler::class => [$decorator = new DummyDecorator()],
        ];
        $middleware = new HandlingMiddleware(new HandlersLocator($handlers), new DecoratorsLocator($decorators));
        $envelop = Envelope::wrap($message);
        $middleware->handle($envelop, new NoneMiddleware());

        $this->assertSame(1, $decorator->count);
    }
}
