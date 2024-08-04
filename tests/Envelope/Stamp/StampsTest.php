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

namespace OpenSolid\Tests\Bus\Envelope\Stamp;

use OpenSolid\Bus\Envelope\Stamp\HandledStamp;
use OpenSolid\Bus\Envelope\Stamp\Stamps;
use PHPUnit\Framework\TestCase;

class StampsTest extends TestCase
{
    public function testAdd(): void
    {
        $stamps = new Stamps([
            new HandledStamp(true),
        ]);

        $this->assertCount(1, $stamps);

        $stamps->add(new HandledStamp(false));

        $this->assertCount(2, $stamps);
    }

    public function testFirst(): void
    {
        $stamps = new Stamps([
            new HandledStamp(true),
            new HandledStamp(false),
        ]);

        $first = $stamps->first(HandledStamp::class);

        $this->assertNotNull($first);
        $this->assertTrue($first->result);
    }

    public function testLast(): void
    {
        $stamps = new Stamps([
            new HandledStamp(true),
            new HandledStamp(false),
        ]);

        $last = $stamps->last(HandledStamp::class);

        $this->assertNotNull($last);
        $this->assertFalse($last->result);
    }

    public function testFilter(): void
    {
        $stamps = new Stamps([
            new HandledStamp(true),
            new HandledStamp(false),
        ]);

        $this->assertCount(2, $stamps);

        $fn = fn (HandledStamp $stamp): bool => $stamp->result;
        $stamps = $stamps->filter(HandledStamp::class, $fn);

        $this->assertCount(1, $stamps);
    }

    public function testMap(): void
    {
        $stamps = new Stamps([
            new HandledStamp(true),
            new HandledStamp(false),
        ]);

        $this->assertCount(2, $stamps);

        $fn = fn (HandledStamp $stamp): bool => $stamp->result;
        $results = $stamps->map(HandledStamp::class, $fn);

        $this->assertSame([true, false], $results);
    }
}
