<?php

namespace OpenSolid\Messenger\Error;

final class MultipleHandlersForObject extends \LogicException
{
    public static function from(string $class, \Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('Object of type "%s" was handled multiple times. Only one handler is expected.', $class), $code, $previous);
    }
}
