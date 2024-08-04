<?php

declare(strict_types=1);

/*
 * This file is part of OpenSolid package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Bus\Handler;

enum MessageHandlersCountPolicy
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
