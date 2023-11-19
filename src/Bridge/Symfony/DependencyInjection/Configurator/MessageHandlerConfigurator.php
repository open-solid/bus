<?php

namespace Yceruto\Messenger\Bridge\Symfony\DependencyInjection\Configurator;

use ReflectionNamedType;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class MessageHandlerConfigurator
{
    /**
     * @param class-string $attributeClass
     */
    public static function configure(ContainerBuilder $builder, string $attributeClass, string $tagName, array $attributes = []): void
    {
        $builder->registerAttributeForAutoconfiguration(
            $attributeClass,
            function (ChildDefinition $definition, object $attribute, \Reflector $reflector) use ($attributeClass, $tagName, $attributes): void {
                if (!$reflector instanceof \ReflectionClass) {
                    return;
                }

                if (!$reflector->hasMethod('__invoke')) {
                    return;
                }

                $reflectionMethod = $reflector->getMethod('__invoke');

                if (0 === $reflectionMethod->getNumberOfParameters()) {
                    return;
                }

                $type = $reflectionMethod->getParameters()[0]->getType();

                if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                    return;
                }

                if ($attribute instanceof $attributeClass) {
                    $definition->addTag($tagName, $attributes + ['message' => $type->getName()]);
                }
            },
        );
    }
}
