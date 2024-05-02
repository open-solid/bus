<?php

namespace OpenSolid\Messenger\Handler;

enum HandlersCountPolicy
{
    case NO_HANDLER;
    case SINGLE_HANDLER;
    case MULTIPLE_HANDLERS;

    public function isNoHandler(): bool
    {
        return self::NO_HANDLER === $this;
    }

    public function isSingleHandler(): bool
    {
        return self::SINGLE_HANDLER === $this;
    }

    public function isMultipleHandlers(): bool
    {
        return self::MULTIPLE_HANDLERS === $this;
    }
}
