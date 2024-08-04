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

namespace OpenSolid\Bus\Error;

final class MultipleHandlersForMessage extends \LogicException
{
    public static function from(string $class, ?\Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected.', $class), $code, $previous);
    }
}
