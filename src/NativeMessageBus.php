<?php

namespace OpenSolid\Bus;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\MiddlewareStack;

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

    public function dispatch(object $message): mixed
    {
        $envelope = Envelope::wrap($message);

        $this->middlewares->handle($envelope);

        return $envelope->unwrap();
    }
}
