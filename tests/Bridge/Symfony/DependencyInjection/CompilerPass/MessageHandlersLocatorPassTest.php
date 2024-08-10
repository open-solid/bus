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

namespace OpenSolid\Tests\Bus\Bridge\Symfony\DependencyInjection\CompilerPass;

use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Handler\MessageHandlersCountPolicy;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NoneMiddleware;
use OpenSolid\Tests\Bus\Fixtures\AsMessageHandler;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use OpenSolid\Tests\Bus\Fixtures\MyMessageHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Argument\AbstractArgument;
use Symfony\Component\DependencyInjection\Compiler\AttributeAutoconfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class MessageHandlersLocatorPassTest extends TestCase
{
    public function testMultipleMessageHandlingProcess(): void
    {
        $container = new ContainerBuilder();
        $this->configureContainer($container, allowMultiple: true);
        $container->compile();

        $envelope = Envelope::wrap(new MyMessage());

        /** @var Middleware $middleware */
        $middleware = $container->get('handling_middleware');
        $middleware->handle($envelope, new NoneMiddleware());

        $result = $envelope->unwrap();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testInvalidSingleMessageHandlingProcess(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Only one handler is allowed for message of type "OpenSolid\Tests\Bus\Fixtures\MyMessage". However, 2 were found: handler_1, handler_2');

        $container = new ContainerBuilder();
        $this->configureContainer($container, allowMultiple: false);
        $container->compile();
    }

    private function configureContainer(ContainerBuilder $container, bool $allowMultiple): void
    {
        $container->register('handling_middleware', HandlingMiddleware::class)
            ->setPublic(true)
            ->setArguments([
                new AbstractArgument('message_handlers_locator'),
                $allowMultiple ? MessageHandlersCountPolicy::MULTIPLE_HANDLERS : MessageHandlersCountPolicy::SINGLE_HANDLER,
            ]);

        $container->register('handler_1', MyMessageHandler::class)
            ->addTag('message_handler', ['class' => MyMessage::class]);

        $container->register('handler_2', MyMessageHandler::class)
            ->addTag('message_handler', ['class' => MyMessage::class]);

        MessageHandlerConfigurator::configure($container, AsMessageHandler::class, 'message_handler');

        $container->addCompilerPass(new AttributeAutoconfigurationPass());
        $container->addCompilerPass(new MessageHandlersLocatorPass('message_handler', 'handling_middleware', [], $allowMultiple));
    }
}
