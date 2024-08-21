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

use OpenSolid\Bus\Decorator\Decorator;

class DummyDecorator implements Decorator
{
    public int $count = 0;
    public array $options = [];

    public function decorate(\Closure $func): \Closure
    {
        return function (mixed ...$args) use ($func): mixed {
            ++$this->count;

            return $func(...$args);
        };
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
