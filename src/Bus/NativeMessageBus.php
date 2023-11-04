<?php

namespace Yceruto\Messenger\Bus;

use Yceruto\Messenger\Middleware\MiddlewareStack;
use Yceruto\Messenger\Model\Envelop;
use Yceruto\Messenger\Model\Message;

final readonly class NativeMessageBus implements MessageBus
{
    public function __construct(private MiddlewareStack $middlewares)
    {
    }

    public function dispatch(Message $message): mixed
    {
        $envelop = Envelop::wrap($message);

        $this->middlewares->handle($envelop);

        return $envelop->result;
    }
}
