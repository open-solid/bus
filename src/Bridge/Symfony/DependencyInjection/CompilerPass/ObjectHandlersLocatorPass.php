<?php

namespace OpenSolid\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class ObjectHandlersLocatorPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function __construct(
        private string $objectHandlerTagName,
        private string $objectHandlerMiddlewareId,
        private bool $allowMultiple = false,
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->objectHandlerMiddlewareId)) {
            return;
        }

        $handlers = $this->findAndSortTaggedServices(
            new TaggedIteratorArgument($this->objectHandlerTagName, 'class'),
            $container,
            [],
            $this->allowMultiple,
        );

        if ($this->allowMultiple) {
            $refs = $handlers;
        } else {
            $refs = [];
            foreach ($handlers as $class => $reference) {
                $refs[$class][] = $reference;
            }
        }

        $middleware = $container->findDefinition($this->objectHandlerMiddlewareId);
        $middleware->replaceArgument(0, new ServiceLocatorArgument($refs));
    }
}
