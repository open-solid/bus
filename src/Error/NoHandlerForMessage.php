<?php

namespace OpenSolid\Messenger\Error;

final class NoHandlerForMessage extends \LogicException
{
    public static function create(string $class, \Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('No handler for message "%s".', $class), $code, $previous);
    }
}
