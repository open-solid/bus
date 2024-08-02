<?php

namespace OpenSolid\Tests\Messenger\Middleware;

use OpenSolid\Messenger\Middleware\NoneMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use OpenSolid\Messenger\Error\NoHandlerForObject;
use OpenSolid\Messenger\Error\MultipleHandlersForObject;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Handler\ObjectHandlersLocator;
use OpenSolid\Messenger\Middleware\HandleObjectMiddleware;
use OpenSolid\Messenger\Middleware\LogObjectHandlingMiddleware;
use OpenSolid\Messenger\Model\Envelope;
use OpenSolid\Tests\Messenger\Fixtures\MyMessage;
use OpenSolid\Tests\Messenger\Fixtures\MyMessageHandler;

class LogObjectHandlingMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info');

        $middleware = new LogObjectHandlingMiddleware($logger);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }
}
