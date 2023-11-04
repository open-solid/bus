<?php

namespace Yceruto\Messenger\Bus;

use Yceruto\Messenger\Handler\HandlerLocator;
use Yceruto\Messenger\Middleware\HandlerMiddleware;
use Yceruto\Messenger\Middleware\HandlerMiddlewareStack;

/**
 * Creates a simple message bus from message handlers.
 */
final class MessageBusFactory
{
    /**
     * @param array<class-string, callable> $handlers
     */
    public static function fromHandlers(array $handlers): MessageBus
    {
        return new NativeMessageBus(new HandlerMiddlewareStack([
            new HandlerMiddleware(new HandlerLocator($handlers)),
        ]));
    }
}
