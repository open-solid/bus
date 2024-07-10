<?php

namespace Middleware;

use OpenSolid\Messenger\Middleware\NoneMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use OpenSolid\Messenger\Error\NoHandlerForMessage;
use OpenSolid\Messenger\Error\MultipleHandlersForMessage;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Handler\HandlersLocator;
use OpenSolid\Messenger\Middleware\HandleMessageMiddleware;
use OpenSolid\Messenger\Middleware\LogMessageMiddleware;
use OpenSolid\Messenger\Model\Envelope;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;
use OpenSolid\Tests\Messenger\Fixtures\MyMessageHandler;

class LogMessageMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info');

        $logMessageMiddleware = new LogMessageMiddleware($logger);
        $logMessageMiddleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }
}
