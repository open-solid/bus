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

namespace OpenSolid\Tests\Bus\Middleware;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Middleware\LoggingMiddleware;
use OpenSolid\Bus\Middleware\NoneMiddleware;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggingMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $middleware = new LoggingMiddleware($logger);
        $middleware->handle(Envelope::wrap(new MyMessage()), new NoneMiddleware());
    }
}
