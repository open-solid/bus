<?php

namespace OpenSolid\Messenger\Bus;

use OpenSolid\Messenger\Model\Message;

interface LazyMessageBus extends MessageBus, FlushableMessageBus
{
    public function dispatch(Message $message): null;
}
