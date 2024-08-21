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

namespace OpenSolid\Bus\Bridge\Symfony\DependencyInjection\CompilerPass;

use OpenSolid\Bus\Decorator\Decorate;
use OpenSolid\Bus\Decorator\Decorator;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

final readonly class HandlingMiddlewarePass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function __construct(
        private string $messageHandlerTagName,
        private string $handlingMiddlewareId,
        private array $exclude = [],
        private bool $allowMultiple = false,
        private string $topic = 'message',
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->handlingMiddlewareId)) {
            return;
        }

        $handlers = $this->findAndSortTaggedServices(
            tagName: new TaggedIteratorArgument($this->messageHandlerTagName, 'class'),
            container: $container,
            exclude: $this->exclude,
        );

        $decorators = [];
        foreach ($handlers as $messageClass => $refs) {
            if (!$this->allowMultiple && \count($refs) > 1) {
                throw new LogicException(\sprintf('Only one handler is allowed for %s of type "%s". However, %d were found: %s', $this->topic, $messageClass, \count($refs), implode(', ', $refs)));
            }

            foreach ($refs as $ref) {
                /** @var class-string $handlerClass */
                $handlerClass = $container->getDefinition((string) $ref)->getClass();

                if (null === $refHandlerClass = $container->getReflectionClass($handlerClass)) {
                    throw new LogicException('Missing reflection class.');
                }

                /** @var array<\ReflectionAttribute<Decorate>> $attributes */
                $attributes = $refHandlerClass->getMethod('__invoke')->getAttributes(Decorate::class, \ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    $instance = $attribute->newInstance();
                    $decorator = $container->getDefinition($instance->id);
                    /** @var class-string $decoratorClass */
                    $decoratorClass = $decorator->getClass();

                    if (!is_subclass_of($decoratorClass, Decorator::class)) {
                        throw new LogicException(\sprintf('The handler decorator "%s" must implement the "%s" interface.', $decoratorClass, Decorator::class));
                    }

                    if (method_exists($decoratorClass, 'setOptions')) {
                        $decorator->addMethodCall('setOptions', [$instance->options]);
                    }

                    $decorators[$handlerClass][$instance->id] = new Reference($instance->id);
                }
            }
        }

        $middleware = $container->findDefinition($this->handlingMiddlewareId);
        $middleware->replaceArgument(0, new ServiceLocatorArgument($handlers));
        $middleware->replaceArgument(1, new ServiceLocatorArgument($decorators));
    }
}
