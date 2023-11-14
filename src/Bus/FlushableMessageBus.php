<?php

namespace Yceruto\Messenger\Bus;

interface FlushableMessageBus
{
    public function flush(): void;
}
