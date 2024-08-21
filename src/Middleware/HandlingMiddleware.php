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

use OpenSolid\Bus\Decorator\Decorator;
use OpenSolid\Bus\Decorator\DecoratorsLocator;
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Envelope\Stamp\HandledStamp;
use OpenSolid\Bus\Error\MultipleHandlersForMessage;
use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\Handler\HandlersCountPolicy;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final readonly class HandlingMiddleware implements Middleware
{
    public function __construct(
        private ContainerInterface $handlers,
        private ContainerInterface $decorators = new DecoratorsLocator([]),
        private HandlersCountPolicy $policy = HandlersCountPolicy::MULTIPLE_HANDLERS,
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
            if ($decorators = $this->decorators->get($handler::class)) {
                $handler = $this->decorate($handler(...), $decorators);
            }

            $result = $handler($envelope->message);

            $envelope->stamps->add(new HandledStamp($result));

            $this->logger->info($this->topic.' of type {message} was handled by {handler}', [
                'message' => $envelope->message::class,
                'handler' => get_debug_type($handler),
            ]);
        }

        $next->handle($envelope);
    }

    /**
     * @param iterable<Decorator> $decorators
     */
    public function decorate(\Closure $func, iterable $decorators): \Closure
    {
        foreach ($decorators as $decorator) {
            $func = $decorator->decorate($func);
        }

        return $func;
    }
}
