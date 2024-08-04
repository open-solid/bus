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
use Symfony\Contracts\Service\ResetInterface;

final class NativeLazyMessageBus implements LazyMessageMessageBus, ResetInterface
{
    private array $messages = [];

    public function __construct(
        private readonly MessageBus $bus,
    ) {
    }

    public function dispatch(Message $message): null
    {
        $this->messages[] = $message;

        return null;
    }

    public function flush(): void
    {
        while ($message = array_shift($this->messages)) {
            $this->bus->dispatch($message);
        }
    }

    public function reset(): void
    {
        $this->messages = [];
    }
}
