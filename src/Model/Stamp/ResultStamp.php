<?php

namespace OpenSolid\Bus\Model\Stamp;

final readonly class ResultStamp
{
    public function __construct(
        public mixed $result,
    ) {
    }
}
