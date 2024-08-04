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
 * A bus responsible for dispatching messages lazily to their handlers.
 * The messages are stored in an internal queue and dispatched when the bus is flushed.
 */
interface LazyMessageMessageBus extends MessageBus, FlushableMessageBus
{
    public function dispatch(Message $message): null;
}
