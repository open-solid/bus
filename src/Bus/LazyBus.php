<?php

namespace OpenSolid\Messenger\Bus;

/**
 * A bus responsible for dispatching objects lazily to their handlers.
 * The objects are stored in an internal queue and dispatched when the bus is flushed.
 */
interface LazyBus extends Bus, FlushableBus
{
    public function dispatch(object $object): null;
}
