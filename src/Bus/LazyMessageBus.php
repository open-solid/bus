<?php

namespace Yceruto\Messenger\Bus;

use Yceruto\Messenger\Model\Message;

interface LazyMessageBus extends MessageBus, FlushableMessageBus
{
    public function dispatch(Message $message): null;
}
