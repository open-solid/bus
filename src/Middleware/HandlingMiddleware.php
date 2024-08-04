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

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Envelope\Stamp\HandledStamp;
use OpenSolid\Bus\Error\MultipleHandlersForMessage;
use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\Handler\MessageHandlersCountPolicy;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final readonly class HandlingMiddleware implements Middleware
{
    public function __construct(
        private ContainerInterface $handlersLocator,
        private MessageHandlersCountPolicy $handlersCountPolicy = MessageHandlersCountPolicy::MULTIPLE_HANDLERS,
        private LoggerInterface $logger = new NullLogger(),
        private string $topic = 'Message',
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $class = $envelope->message::class;

        if (!$this->handlersLocator->has($class)) {
            if ($this->handlersCountPolicy->isNoHandler()) {
                $next->handle($envelope);

                return;
            }

            throw NoHandlerForMessage::from($class);
        }

        $handlers = $this->handlersLocator->get($class);

        if ($this->handlersCountPolicy->isSingleHandler() && count($handlers) > 1) {
            throw MultipleHandlersForMessage::from($class);
        }

        foreach ($handlers as $handler) {
            $result = $handler($envelope->message);

            $envelope->stamps->add(new HandledStamp($result));

            $this->logger->debug($this->topic.' of type {message} was handled by {handler}', [
                'message' => $envelope->message::class,
                'handler' => get_debug_type($handler),
            ]);
        }

        $next->handle($envelope);
    }
}
