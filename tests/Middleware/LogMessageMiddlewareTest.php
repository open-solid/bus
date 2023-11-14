<?php

namespace Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Yceruto\Messenger\Error\NoHandlerForMessage;
use Yceruto\Messenger\Error\SingleHandlerForMessage;
use Yceruto\Messenger\Handler\HandlersCountPolicy;
use Yceruto\Messenger\Handler\HandlersLocator;
use Yceruto\Messenger\Middleware\HandleMessageMiddleware;
use Yceruto\Messenger\Middleware\LogMessageMiddleware;
use Yceruto\Messenger\Model\Envelope;
use Yceruto\Tests\Messenger\Fixtures\MyMessage;
use Yceruto\Tests\Messenger\Fixtures\MyMessageHandler;

class LogMessageMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('info');

        $logMessageMiddleware = new LogMessageMiddleware($logger);
        $logMessageMiddleware->handle(Envelope::wrap(new MyMessage()), static fn () => null);
    }
}
