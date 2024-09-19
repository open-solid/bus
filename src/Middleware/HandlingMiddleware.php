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

namespace OpenSolid\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Envelope\Stamp\HandledStamp;
use OpenSolid\Bus\Error\MultipleHandlersForMessage;
use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\Handler\HandlersCountPolicy;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Yceruto\Decorator\CallableDecorator;
use Yceruto\Decorator\DecoratorInterface;

final readonly class HandlingMiddleware implements Middleware
{
    public function __construct(
        private ContainerInterface $handlers,
        private HandlersCountPolicy $policy = HandlersCountPolicy::MULTIPLE_HANDLERS,
        private DecoratorInterface $decorator = new CallableDecorator(),
        private LoggerInterface $logger = new NullLogger(),
        private string $topic = 'Message',
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        if ($envelope->stamps->has(HandledStamp::class)) {
            $next->handle($envelope);

            return;
        }

        $class = $envelope->message::class;

        if (!$this->handlers->has($class)) {
            if ($this->policy->isNoHandler()) {
                $next->handle($envelope);

                return;
            }

            throw NoHandlerForMessage::from($class);
        }

        $handlers = $this->handlers->get($class);

        if ($this->policy->isSingleHandler() && \count($handlers) > 1) {
            throw MultipleHandlersForMessage::from($class);
        }

        foreach ($handlers as $handler) {
            $handler = $this->decorator->decorate($handler(...));
            $result = $handler($envelope->message);

            $envelope->stamps->add(new HandledStamp($result));

            $this->logger->info($this->topic.' of type {message} was handled by {handler}', [
                'message' => $envelope->message::class,
                'handler' => get_debug_type($handler),
            ]);
        }

        $next->handle($envelope);
    }
}
