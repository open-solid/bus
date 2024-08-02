<?php

namespace OpenSolid\Messenger\Error;

final class NoHandlerForObject extends \LogicException
{
    public static function from(string $class, \Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('No handler for object of type "%s".', $class), $code, $previous);
    }
}
