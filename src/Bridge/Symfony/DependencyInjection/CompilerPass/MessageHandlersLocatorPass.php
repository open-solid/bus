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

namespace OpenSolid\Bus\Bridge\Symfony\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

final readonly class MessageHandlersLocatorPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function __construct(
        private string $messageHandlerTagName,
        private string $handlingMiddlewareId,
        private array $exclude = [],
        private bool $allowMultiple = false,
        private string $topic = 'message',
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->handlingMiddlewareId)) {
            return;
        }

        $handlers = $this->findAndSortTaggedServices(
            tagName: new TaggedIteratorArgument($this->messageHandlerTagName, 'class'),
            container: $container,
            exclude: $this->exclude,
        );

        if (!$this->allowMultiple) {
            foreach ($handlers as $class => $refs) {
                if (count($refs) > 1) {
                    throw new LogicException(sprintf('Only one handler is allowed for %s of type "%s". However, %d were found: %s', $this->topic, $class, count($refs), implode(', ', $refs)));
                }
            }
        }

        $middleware = $container->findDefinition($this->handlingMiddlewareId);
        $middleware->replaceArgument(0, new ServiceLocatorArgument($handlers));
    }
}
