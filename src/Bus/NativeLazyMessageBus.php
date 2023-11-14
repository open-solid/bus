<?php

namespace Yceruto\Messenger\Bus;

use Symfony\Contracts\Service\ResetInterface;
use Yceruto\Messenger\Model\Message;

class NativeLazyMessageBus implements LazyMessageBus, ResetInterface
{
    private array $messages = [];

    public function __construct(private readonly MessageBus $bus)
    {
    }

    public function dispatch(Message $message): null
    {
        $this->messages[] = $message;

        return null;
    }

    public function flush(): void
    {
        foreach ($this->messages as $message) {
            $this->bus->dispatch($message);
        }
        $this->messages = [];
    }

    public function reset(): void
    {
        $this->messages = [];
    }
}
