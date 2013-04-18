Nest
========

Nest is a decorator for invocation key based methods for PHP 5.3 that consists
of just one file and one class.

Usage
-----

Defining the initial namespace and the client:

    $nest = new Nest('foo', new Redis());
    echo $nest; // -> foo

Extending the namespace:

    $nest = new Nest('foo', new Redis());

    echo $nest['bar']; // -> foo:bar
    echo $nest['bar']['baz']; // -> foo:bar:baz

Invoking a method:

    $redis = new Redis();

    $nest = new Nest('foo', $redis);

    $nest->set('ololo'); // makes the proxy to $redis->set('foo', 'ololo');
    $nest['bar']->set('atata'); // $redis->set('foo:bar', 'atata');

    echo $nest['bar']->get(); // -> atata

Source: https://github.com/regeda/php-nest.git