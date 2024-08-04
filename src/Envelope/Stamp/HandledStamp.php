<?php

namespace OpenSolid\Bus\Envelope\Stamp;

final readonly class HandledStamp extends Stamp
{
    public function __construct(
        public mixed $result,
    ) {
    }
}
