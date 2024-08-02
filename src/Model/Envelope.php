<?php

namespace OpenSolid\Messenger\Model;

final class Envelope
{
    public mixed $result = null;

    public static function wrap(object $object): self
    {
        return new self($object);
    }

    private function __construct(
        public readonly object $object,
    ) {
    }
}
