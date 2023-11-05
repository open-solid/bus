<?php

namespace Yceruto\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class MessageHandlersLocatorPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function __construct(
        private string $messageHandlerTagName,
        private string $messageHandlerMiddlewareId,
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        $refs = [];
        $handlers = $this->findAndSortTaggedServices(
            new TaggedIteratorArgument($this->messageHandlerTagName, 'message'),
            $container,
        );
        foreach ($handlers as $message => $reference) {
            $refs[$message][] = $reference;
        }

        $middleware = $container->findDefinition($this->messageHandlerMiddlewareId);
        $middleware->replaceArgument(0, new ServiceLocatorArgument($refs));
    }
}
