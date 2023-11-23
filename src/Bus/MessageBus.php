<?php

namespace OpenSolid\Messenger\Bus;

use OpenSolid\Messenger\Model\Message;

interface MessageBus
{
    public function dispatch(Message $message): mixed;
}
