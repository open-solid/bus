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

namespace OpenSolid\Tests\Bus\Fixtures;

#[AsMessageHandler]
class MyMessageHandler
{
    public function __invoke(MyMessage $message): MyMessage
    {
        return $message;
    }
}
