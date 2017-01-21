<?php
/**
 * This file is part of the prooph/snapshot-store.
 * (c) 2017-2017 prooph software GmbH <contact@prooph.de>
 * (c) 2017-2017 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ProophTest\SnapshotStore;

use PHPUnit\Framework\TestCase;
use Prooph\SnapshotStore\InMemorySnapshotStore;
use Prooph\SnapshotStore\Snapshot;

class InMemorySnapshotStoreTest extends TestCase
{
    /**
     * @test
     */
    public function it_saves_snapshots(): void
    {
        $now = new \DateTimeImmutable();

        $snapshot = new Snapshot(
            'foo',
            'some_id',
            [
                'some' => 'thing'
            ],
            1,
            $now
        );

        $snapshotStore = new InMemorySnapshotStore();
        $snapshotStore->save($snapshot);

        $this->assertSame($snapshot, $snapshotStore->get('foo', 'some_id'));

        $this->assertNull($snapshotStore->get('some', 'invalid'));
    }
}