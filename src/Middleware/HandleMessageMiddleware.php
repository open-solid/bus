<?php

namespace OpenSolid\Messenger\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use OpenSolid\Messenger\Error\SingleHandlerForMessage;
use OpenSolid\Messenger\Error\NoHandlerForMessage;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Model\Envelope;

/**
 * Handles a message with a handler.
 */
final readonly class HandleMessageMiddleware implements Middleware
{
    public function __construct(
        private ContainerInterface $handlersLocator,
        private HandlersCountPolicy $handlersCountPolicy = HandlersCountPolicy::MULTIPLE_HANDLERS,
        private LoggerInterface $logger = new NullLogger(),
        private string $topic = 'Message',
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Envelope $envelope, callable $next): void
    {
        $class = get_class($envelope->message);

        if (!$this->handlersLocator->has($class)) {
            if ($this->handlersCountPolicy->isNoHandler()) {
                $next($envelope);

                return;
            }

            throw NoHandlerForMessage::from($class);
        }

        $handlers = $this->handlersLocator->get($class);

        if ($this->handlersCountPolicy->isSingleHandler() && count($handlers) > 1) {
            throw SingleHandlerForMessage::from($class);
        }

        foreach ($handlers as $handler) {
            $envelope->result = $handler($envelope->message);

            $this->logger->info($this->topic.' {class} was handled', [
                'class' => get_class($envelope->message),
            ]);
        }

        $next($envelope);
    }
}
