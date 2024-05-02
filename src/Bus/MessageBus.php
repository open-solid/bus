<?php

namespace OpenSolid\Messenger\Bus;

use OpenSolid\Messenger\Model\Message;

/**
 * A message bus responsible for dispatching messages to their handlers
 * and returning a result.
 */
interface MessageBus
{
    public function dispatch(Message $message): mixed;
}
