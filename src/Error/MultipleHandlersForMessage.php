<?php

namespace OpenSolid\Bus\Error;

final class MultipleHandlersForMessage extends \LogicException
{
    public static function from(string $class, \Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected.', $class), $code, $previous);
    }
}
