<?php

namespace Yceruto\Messenger\Error;

class SingleHandlerForMessage extends \LogicException
{
    public static function from(string $class): self
    {
        return new self(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected.', $class));
    }
}
