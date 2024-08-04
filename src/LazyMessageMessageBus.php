<?php

namespace OpenSolid\Bus;

use OpenSolid\Bus\Envelope\Message;

/**
 * A bus responsible for dispatching messages lazily to their handlers.
 * The messages are stored in an internal queue and dispatched when the bus is flushed.
 */
interface LazyMessageMessageBus extends MessageBus, FlushableMessageBus
{
    public function dispatch(Message $message): null;
}
