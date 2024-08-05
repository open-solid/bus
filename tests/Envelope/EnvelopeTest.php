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

namespace OpenSolid\Tests\Bus\Envelope;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Envelope\Stamp\HandledStamp;
use OpenSolid\Tests\Bus\Fixtures\MyMessage;
use PHPUnit\Framework\TestCase;

class EnvelopeTest extends TestCase
{
    public function testWrappingSelfEnvelop(): void
    {
        $message = Envelope::wrap(new MyMessage(), [new HandledStamp(true)]);

        $this->assertSame($message, Envelope::wrap($message));
    }

    public function testNullResult(): void
    {
        $envelope = Envelope::wrap(new MyMessage());

        $result = $envelope->unwrap();

        $this->assertTrue($result->isNone());
    }

    public function testSingleResult(): void
    {
        $envelope = Envelope::wrap(new MyMessage());
        $envelope->stamps->add(new HandledStamp(true));

        $result = $envelope->unwrap();

        $this->assertTrue($result->isSome());
    }

    public function testMultipleResults(): void
    {
        $envelope = Envelope::wrap(new MyMessage());
        $envelope->stamps->add(new HandledStamp(true));
        $envelope->stamps->add(new HandledStamp(false));

        $result = $envelope->unwrap();

        $this->assertSame([true, false], $result->unwrap());
    }
}
