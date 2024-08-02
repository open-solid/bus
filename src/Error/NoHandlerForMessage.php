<?php

namespace OpenSolid\Bus\Error;

final class NoHandlerForMessage extends \LogicException
{
    public static function from(string $class, \Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('No handler for message of type "%s".', $class), $code, $previous);
    }
}
