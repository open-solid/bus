<?php

namespace OpenSolid\Messenger\Bus;

/**
 * A bus that can be flushed to dispatch all objects that have been queued.
 * Useful for dispatching objects as late as possible in a request lifecycle.
 */
interface FlushableBus
{
    public function flush(): void;
}
