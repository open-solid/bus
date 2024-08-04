<?php

namespace OpenSolid\Bus;

use OpenSolid\Bus\Envelope\Message;

/**
 * A bus responsible for dispatching messages to their handlers
 * and returning a result.
 */
interface MessageBus
{
    public function dispatch(Message $message): mixed;
}
