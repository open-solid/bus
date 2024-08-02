<?php

namespace OpenSolid\Tests\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Argument\AbstractArgument;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Compiler\AttributeAutoconfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OpenSolid\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass\ObjectHandlersLocatorPass;
use OpenSolid\Messenger\Bridge\Symfony\DependencyInjection\Configurator\ObjectHandlerConfigurator;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Middleware\HandleObjectMiddleware;
use OpenSolid\Tests\Messenger\Fixtures\AsMessageHandler;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;
use OpenSolid\Tests\Messenger\Fixtures\MyMessageHandler;

class ObjectHandlersLocatorPassTest extends TestCase
{
    public function testProcess(): void
    {
        $container = new ContainerBuilder();
        $container->register('cqs.message.handle_middleware', HandleObjectMiddleware::class)
            ->setPublic(true)
            ->setArguments([
                new AbstractArgument('cqs.message.handlers_locator'),
                HandlersCountPolicy::MULTIPLE_HANDLERS,
            ]);

        $container->register(MyMessageHandler::class)
            ->addTag('cqs.message.handler', ['class' => MyMessage::class]);

        ObjectHandlerConfigurator::configure($container, AsMessageHandler::class, 'cqs.message.handler');

        $container->addCompilerPass(new AttributeAutoconfigurationPass());
        $container->addCompilerPass(new ObjectHandlersLocatorPass('cqs.message.handler', 'cqs.message.handle_middleware'));
        $container->compile();

        $this->assertTrue($container->has('cqs.message.handle_middleware'));
    }
}
