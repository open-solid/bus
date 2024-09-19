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

use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\DecoratorInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class CastToArray extends DecoratorAttribute implements DecoratorInterface
{
    public function decorate(\Closure $func): \Closure
    {
        return static function (mixed ...$args) use ($func): array {
            return (array) $func(...$args);
        };
    }

    public function decoratedBy(): string
    {
        return self::class;
    }
}
