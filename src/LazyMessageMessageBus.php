<?php

namespace OpenSolid\Bus;

/**
 * A bus responsible for dispatching messages lazily to their handlers.
 * The messages are stored in an internal queue and dispatched when the bus is flushed.
 */
interface LazyMessageMessageBus extends MessageBus, FlushableMessageBus
{
    public function dispatch(object $message): null;
}
