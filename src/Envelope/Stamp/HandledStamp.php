<?php

namespace OpenSolid\Bus\Envelope\Stamp;

final readonly class HandledStamp
{
    public function __construct(
        public mixed $result,
    ) {
    }
}
