<?php

namespace OpenSolid\Messenger\Error;

final class NoHandlerForMessage extends \LogicException
{
    public static function create(string $class): self
    {
        return new self(sprintf('No handler for message "%s".', $class));
    }
}
