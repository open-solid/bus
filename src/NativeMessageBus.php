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

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Envelope\Message;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\MiddlewareStack;
use Std\Type\Option;

final readonly class NativeMessageBus implements MessageBus
{
    private MiddlewareStack $middlewares;

    /**
     * @param iterable<Middleware> $middlewares
     */
    public function __construct(iterable $middlewares)
    {
        $this->middlewares = new MiddlewareStack($middlewares);
    }

    public function dispatch(Message $message): Option
    {
        $envelope = Envelope::wrap($message);

        $this->middlewares->handle($envelope);

        return $envelope->unwrap();
    }
}
