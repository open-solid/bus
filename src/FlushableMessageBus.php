<?php

declare(strict_types=1);

/*
 * This file is part of Option Type package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Bus;

/**
 * A bus that can be flushed to dispatch all messages that have been queued.
 * Useful for dispatching messages as late as possible in a request lifecycle.
 */
interface FlushableMessageBus
{
    public function flush(): void;
}
