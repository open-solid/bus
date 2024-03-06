<?php

namespace OpenSolid\Messenger\Error;

final class MultipleHandlersForMessage extends \LogicException
{
    public static function create(string $class): self
    {
        return new self(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected.', $class));
    }
}
