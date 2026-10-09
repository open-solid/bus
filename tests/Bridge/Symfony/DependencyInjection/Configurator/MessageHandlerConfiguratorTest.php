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

namespace OpenSolid\Tests\Bus\Bridge\Symfony\DependencyInjection\Configurator;

use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\CompilerPass\HandlingMiddlewarePass;
use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Handler\HandlersCountPolicy;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NoneMiddleware;
use OpenSolid\Tests\Bus\Fixtures\AsMessageHandler;
use OpenSolid\Tests\Bus\Fixtures\MyBuiltinUnionMessageHandler;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use OpenSolid\Tests\Bus\Fixtures\MyMessageHandler;
use OpenSolid\Tests\Bus\Fixtures\MyOtherMessage;
use OpenSolid\Tests\Bus\Fixtures\MyUnionMessageHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Argument\AbstractArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class MessageHandlerConfiguratorTest extends TestCase
{
    public function testSingleTypedHandlerIsTaggedWithItsMessageClass(): void
    {
        $container = $this->buildContainer(MyMessageHandler::class);

        $this->assertSame(
            [['class' => MyMessage::class]],
            $container->getDefinition('handler')->getTag('message_handler'),
        );
    }

    public function testUnionTypedHandlerIsTaggedOncePerMessageClass(): void
    {
        $container = $this->buildContainer(MyUnionMessageHandler::class);

        $this->assertSame(
            [['class' => MyMessage::class], ['class' => MyOtherMessage::class]],
            $container->getDefinition('handler')->getTag('message_handler'),
        );
    }

    public function testUnionTypedHandlerHandlesEveryMessageOfItsUnion(): void
    {
        $container = $this->buildContainer(MyUnionMessageHandler::class);

        /** @var Middleware $middleware */
        $middleware = $container->get('handling_middleware');

        foreach ([new MyMessage(), new MyOtherMessage()] as $message) {
            $envelope = Envelope::wrap($message);
            $middleware->handle($envelope, new NoneMiddleware());

            $this->assertSame($message::class, $envelope->unwrap());
        }
    }

    public function testMessageClassAttributeIsConfigurable(): void
    {
        $container = $this->buildContainer(MyUnionMessageHandler::class, ['bus' => 'event.bus'], 'handles');

        $this->assertSame(
            [['bus' => 'event.bus', 'handles' => MyMessage::class], ['bus' => 'event.bus', 'handles' => MyOtherMessage::class]],
            $container->getDefinition('handler')->getTag('message_handler'),
        );
    }

    public function testBuiltinTypeInUnionFailsTheContainerBuild(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(\sprintf('Invalid type "string" in the union type of parameter "$message" of "%s::__invoke()"', MyBuiltinUnionMessageHandler::class));

        $this->buildContainer(MyBuiltinUnionMessageHandler::class);
    }

    /**
     * @param class-string $handlerClass
     */
    private function buildContainer(string $handlerClass, array $attributes = [], string $messageClassAttribute = 'class'): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $container->register('handling_middleware', HandlingMiddleware::class)
            ->setPublic(true)
            ->setArguments([
                new AbstractArgument('message_handlers_locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
            ]);

        $container->register('handler', $handlerClass)
            ->setAutoconfigured(true)
            ->setPublic(true);

        MessageHandlerConfigurator::configure($container, AsMessageHandler::class, 'message_handler', $attributes, $messageClassAttribute);

        if ('class' === $messageClassAttribute) {
            $container->addCompilerPass(new HandlingMiddlewarePass('message_handler', 'handling_middleware'));
        } else {
            $container->removeDefinition('handling_middleware');
        }

        $container->compile();

        return $container;
    }
}
