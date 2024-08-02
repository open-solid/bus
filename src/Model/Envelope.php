<?php

namespace OpenSolid\Messenger\Model;

final class Envelope
{
    public mixed $result = null;

    public static function wrap(Message $message): self
    {
        return new self($message);
    }

    private function __construct(
        public readonly Message $message,
    ) {
    }
}
