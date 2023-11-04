<?php

namespace Yceruto\Messenger\Error;

class HandlerNotFound extends \LogicException
{
    public static function from(string $class): self
    {
        return new self(sprintf('Handler not found for message "%s"', $class));
    }
}
