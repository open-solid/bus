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

namespace OpenSolid\Bus\Bridge\Symfony\DependencyInjection\Configurator;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

final readonly class MessageHandlerConfigurator
{
    /**
     * A union-typed __invoke() gets one tag per class in the union.
     *
     * The tag attribute holding the message class defaults to "class" (read by HandlingMiddlewarePass);
     * pass "handles" for Symfony Messenger, which otherwise guesses every class of the union again on each tag.
     *
     * @param class-string $attributeClass
     */
    public static function configure(ContainerBuilder $builder, string $attributeClass, string $tagName, array $attributes = [], string $messageClassAttribute = 'class'): void
    {
        $builder->registerAttributeForAutoconfiguration(
            $attributeClass,
            static function (ChildDefinition $definition, object $attribute, \Reflector $reflector) use ($attributeClass, $tagName, $attributes, $messageClassAttribute): void {
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

                if (!$attribute instanceof $attributeClass) {
                    return;
                }

                foreach (self::messageClasses($reflector, $reflectionMethod->getParameters()[0]) as $messageClass) {
                    $definition->addTag($tagName, $attributes + [$messageClassAttribute => $messageClass]);
                }
            },
        );
    }

    /**
     * @return list<string>
     */
    private static function messageClasses(\ReflectionClass $handler, \ReflectionParameter $parameter): array
    {
        $type = $parameter->getType();

        if ($type instanceof \ReflectionNamedType) {
            return $type->isBuiltin() ? [] : [$type->getName()];
        }

        if (!$type instanceof \ReflectionUnionType) {
            return [];
        }

        $classes = [];

        foreach ($type->getTypes() as $member) {
            if (!$member instanceof \ReflectionNamedType || $member->isBuiltin()) {
                throw new LogicException(\sprintf('Invalid type "%s" in the union type of parameter "$%s" of "%s::__invoke()": every member of the union must be a message class.', $member, $parameter->getName(), $handler->getName()));
            }

            $classes[] = $member->getName();
        }

        return $classes;
    }

    private function __construct()
    {
    }
}
