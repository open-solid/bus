<?php

namespace Yceruto\Messenger\Model;

final class Envelop
{
    public mixed $result = null;

    public static function wrap(Message $message): self
    {
        return new self($message);
    }

    private function __construct(public readonly Message $message)
    {
    }
}
