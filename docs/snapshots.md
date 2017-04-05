# Snapshots

One of the counter-arguments against Event-Sourcing you might heard about is that replaying events takes too much time.

Replaying 20 events for an aggregate is fast, 50 is ok, but 100 events and more become slow depending on the data of the events and the operations needed to replay them.
A DDD rule of thumb says aggregates should be kept small. Keeping that in mind you should be fine with 50 events per aggregate
in most cases.

## But my aggregates record tons of events!
If aggregate reconstitution gets slow you can add an additional layer to the system which
is capable of providing aggregate snapshots.

## Creating snapshots from event streams

This feature is provided by the [prooph/snapshotter](https://github.com/prooph/snapshotter) package.
Please refer to the docs of the package to learn more about it.

Also choose one of the `Prooph\*SnapshotStore` to take snapshots.
Inject the snapshot store into an aggregate repository and the repository will use the snapshot store to speed up
aggregate loading.

Our example application [proophessor-do](https://github.com/prooph/proophessor-do) contains a snapshotting tutorial.

## Using a different Serializer. 

By default we use php build in serialize/unserialize methods to around aggregate persistence. If you would like to change it you can do that since v1.1. 

You can use the provided CallbackSerializer to do this.

```
new PdoSnapshotStore(
   $connection,
   $config['snapshot_table_map'],
   $config['default_snapshot_table_name'],
   new CallbackSerializer('igbinary_serialize', 'igbinary_unserialize')
);
```

If you are using the interop factories all you have to do is create a Factory for `Prooph\SnapshotStore\Serializer` and add that as dependency;

```
<?php

return [
	'dependencies' => [
		'factories' => \Prooph\SnapshotStore\Serializer::class => My\CallbackSerializerFactory::class,
		],
	],
]
``` 

*Note: All SnapshotStores ship with interop factories to ease set up.*

## Usage

Using [Prooph Event-Sourcing](https://github.com/prooph/event-sourcing/) you need to install [Prooph Snapshotter](https://github.com/prooph/snapshotter).
Check the documentation there on how to use it.

Using [Prooph Micro](https://github.com/prooph/micro/) the usage is a simple php function.
