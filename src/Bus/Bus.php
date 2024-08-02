<?php

namespace OpenSolid\Messenger\Bus;

/**
 * A bus responsible for dispatching objects to their handlers
 * and returning a result.
 */
interface Bus
{
    public function dispatch(object $object): mixed;
}
