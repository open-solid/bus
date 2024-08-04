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

namespace OpenSolid\Bus\Envelope;

use OpenSolid\Bus\Envelope\Stamp\HandledStamp;
use OpenSolid\Bus\Envelope\Stamp\Stamps;

final readonly class Envelope
{
    public Message $message;
    public Stamps $stamps;

    public static function wrap(Message $message, array $stamps = []): self
    {
        return new self($message, $stamps);
    }

    public function unwrap(): mixed
    {
        $results = $this->stamps
            ->filter(HandledStamp::class, fn (HandledStamp $stamp): bool => null !== $stamp->result)
            ->map(HandledStamp::class, fn (HandledStamp $stamp): mixed => $stamp->result);

        return match (\count($results)) {
            0 => null,
            1 => $results[0],
            default => $results,
        };
    }

    private function __construct(Message $message, array $stamps)
    {
        $this->message = $message;
        $this->stamps = new Stamps($stamps);
    }
}
