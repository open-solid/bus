<?php

namespace OpenSolid\Messenger\Bus;

use OpenSolid\Messenger\Model\Message;

/**
 * A message bus responsible for dispatching messages lazily to their handlers.
 * The messages are stored in an internal queue and dispatched when the bus is flushed.
 */
interface LazyMessageBus extends MessageBus, FlushableMessageBus
{
    public function dispatch(Message $message): null;
}
