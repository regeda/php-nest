Nest
========

Nest is a decorator for key based methods invocation. The class for PHP 5.3 consists
of just one file.

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

    $user = new Nest('user', $redis);

    // getter

    foreach ($ids as $id) {
        echo $user[$id]['username']->get(); // makes the proxy to $redis->get('user:$id:username')
    }

    // setter

    $user[$id]['age']->set(18); // $redis->set('user:$id:age', 18)

    

Source: https://github.com/regeda/php-nest
