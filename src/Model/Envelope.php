<?php

namespace OpenSolid\Bus\Model;

final class Envelope
{
    public mixed $result = null;

    public static function wrap(object $message): self
    {
        return new self($message);
    }

    private function __construct(
        public readonly object $message,
    ) {
    }
}
