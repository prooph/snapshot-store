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

namespace Prooph\SnapshotStore;

interface Serializer
{
    public function serialize($data): string;

    public function unserialize(string $data);
}
