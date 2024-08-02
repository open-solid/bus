<?php

namespace OpenSolid\Tests\Bus\Middleware;

use OpenSolid\Bus\Middleware\NoneMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\Error\MultipleHandlersForMessage;
use OpenSolid\Bus\Handler\MessageHandlersCountPolicy;
use OpenSolid\Bus\Handler\MessageHandlersLocator;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\LoggingMiddleware;
use OpenSolid\Bus\Model\Envelope;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use OpenSolid\Tests\Bus\Fixtures\MyMessageHandler;

class LoggingMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info');

        $middleware = new LoggingMiddleware($logger);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }
}
