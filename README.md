# Yocto

[![Master branch build status][ico-build]][travis]
[![PHP ~5.5][ico-engine]][lang]

**Yocto** is a tiny, stack-based php app framework that acts as nothing more than a wrapper for whichever components you choose.
A yocto app instance lets you build a (FIFO) stack of callables, each of which gets called with the app instance as a sole argument. The app instance is constructed with a callback that handles, or delegates service resolution.

The easiest way to install Yocto is via [Composer][package].
```json
{
    "require": {
        "silawrenc/yocto": "*"
    }
}
```

## API

Here's a quick example that showcases the API.
```php

// 1. construct an instance passing in a callback for service resolution
$app = new Yocto([$container, 'get']);

// 2. add callbacks to the stack (FIFO)
$app->add(function ($app) {
    // returning false inside a callback would ensure no further callbacks are executed
    return !$app->get('auth')->isAuthenticated();
});

$app->add(function () {
    echo 'Hello world';
});

// 3. kick things off
$app->run();
// ...outputs hello world iff isAuthenticated is true

```php


[travis]: https://travis-ci.org/silawrenc/yocto
[package]: https://packagist.org/packages/silawrenc/yocto
[lang]: http://php.net
[ico-build]: http://img.shields.io/travis/silawrenc/yocto/master.svg?style=flat
[ico-engine]: http://img.shields.io/badge/php-~5.5-8892BF.svg?style=flat
