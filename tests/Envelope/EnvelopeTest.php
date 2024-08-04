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
    public function testNullResult(): void
    {
        $envelope = Envelope::wrap(new MyMessage());

        $this->assertNull($envelope->unwrap());
    }

    public function testSingleResult(): void
    {
        $envelope = Envelope::wrap(new MyMessage());

        $envelope->stamps->add(new HandledStamp(true));

        $this->assertTrue($envelope->unwrap());
    }

    public function testMultipleResults(): void
    {
        $envelope = Envelope::wrap(new MyMessage());

        $envelope->stamps->add(new HandledStamp(true));
        $envelope->stamps->add(new HandledStamp(false));

        $this->assertSame([true, false], $envelope->unwrap());
    }
}
