<?php

namespace OpenSolid\Messenger\Bus;

use OpenSolid\Messenger\Middleware\Middleware;
use OpenSolid\Messenger\Middleware\MiddlewareStack;
use OpenSolid\Messenger\Model\Envelope;

final readonly class NativeBus implements Bus
{
    private MiddlewareStack $middlewares;

    /**
     * @param iterable<Middleware> $middlewares
     */
    public function __construct(iterable $middlewares)
    {
        $this->middlewares = new MiddlewareStack($middlewares);
    }

    public function dispatch(object $object): mixed
    {
        $envelope = Envelope::wrap($object);

        $this->middlewares->handle($envelope);

        return $envelope->result;
    }
}
