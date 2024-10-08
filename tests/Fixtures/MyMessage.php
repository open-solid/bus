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

namespace OpenSolid\Tests\Bus\Fixtures;

use OpenSolid\Bus\Envelope\Message;

/**
 * @extends Message<self>
 */
readonly class MyMessage extends Message
{
    public function __construct(
        public string $foo = 'bar',
    ) {
    }
}
