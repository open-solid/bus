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

namespace OpenSolid\Bus\Bridge\Symfony\DependencyInjection\Configurator;

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

                if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                    return;
                }

                if ($attribute instanceof $attributeClass) {
                    $definition->addTag($tagName, $attributes + ['class' => $type->getName()]);
                }
            },
        );
    }
}
