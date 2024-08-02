<?php

namespace OpenSolid\Bus\Model;

use OpenSolid\Bus\Model\Stamp\ResultStamp;
use OpenSolid\Bus\Model\Stamp\Stamps;

final readonly class Envelope
{
    public object $message;
    public Stamps $stamps;

    public static function wrap(object $message, array $stamps = []): self
    {
        return new self($message, $stamps);
    }

    public function addResult(mixed $result): void
    {
        $this->stamps->add(new ResultStamp($result));
    }

    public function results(): mixed
    {
        $results = $this->stamps->map(
            ResultStamp::class,
            fn (ResultStamp $stamp): mixed => $stamp->result,
        );

        return match (\count($results)) {
            0 => null,
            1 => $results[0],
            default => $results,
        };
    }

    private function __construct(object $message, array $stamps)
    {
        $this->message = $message;
        $this->stamps = new Stamps($stamps);
    }
}
