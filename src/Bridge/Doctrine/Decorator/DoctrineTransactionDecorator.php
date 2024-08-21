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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use OpenSolid\Bus\Decorator\Decorator;

class DoctrineTransactionDecorator implements Decorator
{
    private array $options = [];

    public function __construct(
        private readonly ManagerRegistry $registry,
    ) {
    }

    public function decorate(\Closure $func): \Closure
    {
        $manager = $this->registry->getManager($this->options['name'] ?? null);

        if (!$manager instanceof EntityManagerInterface) {
            throw new \LogicException('Doctrine ORM entity managers are only supported');
        }

        return static function (mixed ...$args) use ($manager, $func): mixed {
            return $manager->wrapInTransaction(static fn (): mixed => $func(...$args));
        };
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
