<?php

namespace Yceruto\Messenger\Bus;

use Yceruto\Messenger\Middleware\Middleware;
use Yceruto\Messenger\Middleware\MiddlewareStack;
use Yceruto\Messenger\Model\Envelop;
use Yceruto\Messenger\Model\Message;

final readonly class NativeMessageBus implements MessageBus
{
    private MiddlewareStack $stack;

    /**
     * @param iterable<Middleware> $middlewares
     */
    public function __construct(iterable $middlewares)
    {
        $this->stack = new MiddlewareStack($middlewares);
    }

    public function dispatch(Message $message): mixed
    {
        $envelop = Envelop::wrap($message);

        $this->stack->handle($envelop);

        return $envelop->result;
    }
}
