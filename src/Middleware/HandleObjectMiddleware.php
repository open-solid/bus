<?php

namespace OpenSolid\Messenger\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use OpenSolid\Messenger\Error\MultipleHandlersForObject;
use OpenSolid\Messenger\Error\NoHandlerForObject;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Model\Envelope;

final readonly class HandleObjectMiddleware implements Middleware
{
    public function __construct(
        private ContainerInterface $handlersLocator,
        private HandlersCountPolicy $handlersCountPolicy = HandlersCountPolicy::MULTIPLE_HANDLERS,
        private LoggerInterface $logger = new NullLogger(),
        private string $topic = 'Object',
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $class = $envelope->object::class;

        if (!$this->handlersLocator->has($class)) {
            if ($this->handlersCountPolicy->isNoHandler()) {
                $next->handle($envelope);

                return;
            }

            throw NoHandlerForObject::from($class);
        }

        $handlers = $this->handlersLocator->get($class);

        if ($this->handlersCountPolicy->isSingleHandler() && count($handlers) > 1) {
            throw MultipleHandlersForObject::from($class);
        }

        foreach ($handlers as $handler) {
            $envelope->result = $handler($envelope->object);

            $this->logger->info($this->topic.' of type {class} was handled', [
                'class' => $envelope->object::class,
            ]);
        }

        $next->handle($envelope);
    }
}
