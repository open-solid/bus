<?php

namespace Yceruto\Messenger\Bus;

use Yceruto\Messenger\Handler\HandlersLocator;
use Yceruto\Messenger\Middleware\HandlerMiddleware;

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
        return new NativeMessageBus([
            new HandlerMiddleware(new HandlersLocator($handlers)),
        ]);
    }
}
