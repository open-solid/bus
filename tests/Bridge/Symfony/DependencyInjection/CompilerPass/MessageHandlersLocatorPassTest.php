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

namespace OpenSolid\Tests\Bus\Bridge\Symfony\DependencyInjection\CompilerPass;

use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;
use OpenSolid\Bus\Handler\MessageHandlersCountPolicy;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Tests\Bus\Fixtures\AsMessageHandler;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use OpenSolid\Tests\Bus\Fixtures\MyMessageHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Argument\AbstractArgument;
use Symfony\Component\DependencyInjection\Compiler\AttributeAutoconfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessageHandlersLocatorPassTest extends TestCase
{
    public function testProcess(): void
    {
        $container = new ContainerBuilder();
        $container->register('cqs.message.handle_middleware', HandlingMiddleware::class)
            ->setPublic(true)
            ->setArguments([
                new AbstractArgument('cqs.message.handlers_locator'),
                MessageHandlersCountPolicy::MULTIPLE_HANDLERS,
            ]);

        $container->register(MyMessageHandler::class)
            ->addTag('cqs.message.handler', ['class' => MyMessage::class]);

        MessageHandlerConfigurator::configure($container, AsMessageHandler::class, 'cqs.message.handler');

        $container->addCompilerPass(new AttributeAutoconfigurationPass());
        $container->addCompilerPass(new MessageHandlersLocatorPass('cqs.message.handler', 'cqs.message.handle_middleware'));
        $container->compile();

        $this->assertTrue($container->has('cqs.message.handle_middleware'));
    }
}
