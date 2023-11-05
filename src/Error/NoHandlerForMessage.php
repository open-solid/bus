<?php

namespace Yceruto\Messenger\Error;

class NoHandlerForMessage extends \LogicException
{
    public static function from(string $class): self
    {
        return new self(sprintf('No handler for message "%s".', $class));
    }
}
