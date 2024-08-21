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

namespace OpenSolid\Tests\Bus;

use OpenSolid\Bus\Handler\HandlersLocator;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\NativeMessageBus;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use PHPUnit\Framework\TestCase;

class NativeMessageBusTest extends TestCase
{
    public function testDispatchAndHandling(): void
    {
        $handlers = [
            MyMessage::class => [
                static fn (MyMessage $message): MyMessage => $message,
            ],
        ];
        $middlewares = [
            new HandlingMiddleware(new HandlersLocator($handlers)),
        ];
        $bus = new NativeMessageBus($middlewares);
        $message = new MyMessage();

        $result = $bus->dispatch($message);

        $this->assertSame($message, $result);
    }
}
