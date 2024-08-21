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

namespace OpenSolid\Bus\Bridge\Doctrine\Decorator;

use OpenSolid\Bus\Decorator\Decorate;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
readonly class Transactional extends Decorate
{
    /**
     * @param string|null $entityManagerName the entity manager name (null for the default one)
     */
    public function __construct(
        ?string $entityManagerName = null,
    ) {
        parent::__construct(DoctrineTransactionDecorator::class, [
            'name' => $entityManagerName,
        ]);
    }
}
