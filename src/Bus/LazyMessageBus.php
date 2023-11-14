<?php

namespace Yceruto\Messenger\Bus;

use Yceruto\Messenger\Model\Message;

interface LazyMessageBus extends MessageBus
{
    public function dispatch(Message $message): null;

    public function flush(): void;
}
