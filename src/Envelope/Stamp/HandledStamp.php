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

namespace OpenSolid\Bus\Envelope\Stamp;

final readonly class HandledStamp extends Stamp
{
    public function __construct(
        public mixed $result,
    ) {
    }
}