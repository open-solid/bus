<?php

namespace OpenSolid\Bus;

/**
 * A bus responsible for dispatching messages to their handlers
 * and returning a result.
 */
interface MessageBus
{
    public function dispatch(object $message): mixed;
}
