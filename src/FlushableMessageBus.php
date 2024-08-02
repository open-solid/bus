<?php

namespace OpenSolid\Bus;

/**
 * A bus that can be flushed to dispatch all messages that have been queued.
 * Useful for dispatching messages as late as possible in a request lifecycle.
 */
interface FlushableMessageBus
{
    public function flush(): void;
}
