<?php

/**
 * This file is part of prooph/snapshot-store.
 * (c) 2017-2021 prooph software GmbH <contact@prooph.de>
 * (c) 2017-2021 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\SnapshotStore\Container;

use Interop\Config\ConfigurationTrait;
use Interop\Config\RequiresConfigId;
use Prooph\SnapshotStore\CompositeSnapshotStore;
use Psr\Container\ContainerInterface;

final class CompositeSnapshotStoreFactory implements RequiresConfigId
{
    use ConfigurationTrait;

    /**
     * @var string
     */
    private $configId;

    /**
     * Creates a new instance from a specified config, specifically meant to be used as static factory.
     *
     * In case you want to use another config key than provided by the factories, you can add the following factory to
     * your config:
     *
     * <code>
     * <?php
     * return [
     *     CompositeSnapshotStore::class => [CompositeSnapshotStoreFactory::class, 'service_name'],
     * ];
     * </code>
     *
     * @throws \InvalidArgumentException
     */
    public static function __callStatic(string $name, array $arguments): CompositeSnapshotStore
    {
        if (! isset($arguments[0]) || ! $arguments[0] instanceof ContainerInterface) {
            throw new \InvalidArgumentException(
                \sprintf('The first argument must be of type %s', ContainerInterface::class)
            );
        }

        return (new static($name))->__invoke($arguments[0]);
    }

    public function __invoke(ContainerInterface $container): CompositeSnapshotStore
    {
        $config = $container->get('config');
        $config = $this->options($config, $this->configId);

        $snapshotStores = [];

        foreach ($config as $snapshotStoreName) {
            $snapshotStores[] = $container->get($snapshotStoreName);
        }

        return new CompositeSnapshotStore(...$snapshotStores);
    }

    public function __construct(string $configId = 'default')
    {
        $this->configId = $configId;
    }

    public function dimensions(): iterable
    {
        return ['prooph', 'composite_snapshot_store'];
    }
}
