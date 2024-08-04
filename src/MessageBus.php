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

namespace OpenSolid\Bus;

use OpenSolid\Bus\Envelope\Message;

/**
 * A bus responsible for dispatching messages to their handlers
 * and returning a result.
 */
interface MessageBus
{
    public function dispatch(Message $message): mixed;
}
