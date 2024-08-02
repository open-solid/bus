<?php

namespace OpenSolid\Messenger\Bus;

use OpenSolid\Messenger\Middleware\Middleware;
use OpenSolid\Messenger\Middleware\MiddlewareStack;
use OpenSolid\Messenger\Model\Envelope;
use OpenSolid\Messenger\Model\Message;

final readonly class NativeMessageBus implements MessageBus
{
    private MiddlewareStack $middlewares;

    /**
     * @param iterable<Middleware> $middlewares
     */
    public function __construct(iterable $middlewares)
    {
        $this->middlewares = new MiddlewareStack($middlewares);
    }

    public function dispatch(Message $message): mixed
    {
        $envelope = Envelope::wrap($message);

        $this->middlewares->handle($envelope);

        return $envelope->result;
    }
}
