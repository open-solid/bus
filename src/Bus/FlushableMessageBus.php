<?php

namespace OpenSolid\Messenger\Bus;

interface FlushableMessageBus
{
    public function flush(): void;
}
