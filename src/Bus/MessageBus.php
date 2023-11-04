<?php

namespace Yceruto\Messenger\Bus;

use Yceruto\Messenger\Model\Message;

interface MessageBus
{
    public function dispatch(Message $message): mixed;
}
