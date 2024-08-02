<?php

namespace OpenSolid\Bus\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use OpenSolid\Bus\Error\MultipleHandlersForMessage;
use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\Handler\MessageHandlersCountPolicy;
use OpenSolid\Bus\Model\Envelope;

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
            $envelope->result = $handler($envelope->message);

            $this->logger->info($this->topic.' of type {class} was handled', [
                'class' => $envelope->message::class,
            ]);
        }

        $next->handle($envelope);
    }
}
