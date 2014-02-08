# League\Di

[![Build Status](https://travis-ci.org/php-loep/di.png?branch=master)](https://travis-ci.org/php-loep/di)
[![Dependencies Status](https://d2xishtp1ojlk0.cloudfront.net/d/11641448)](http://depending.in/php-loep/di)
[![Coverage Status](https://coveralls.io/repos/php-loep/di/badge.png?branch=master)](https://coveralls.io/r/php-loep/di?branch=master)
[![Total Downloads](https://poser.pugx.org/league/di/downloads.png)](https://packagist.org/packages/league/di)
[![Latest Stable Version](https://poser.pugx.org/league/di/v/stable.png)](https://packagist.org/packages/league/di)

The League\Di library provides a fast and powerful Dependency Injection Container for your application.

## Install

Via Composer
```json
{
    "require": {
        "league/di": ">=1.1"
    }
}
```
## Usage

### Get a Container Object
```php
include 'vendor/autoload.php';

$container = new League\Di\Container;
```
### Bind a concrete class to an interface
```php
$container->bind('\Foo\Bar\BazInterface', '\Foo\Bar\Baz');
```
### Automatic Dependency Resolution

The Di Container is able to recursively resolve objects and their dependencies by inspecting the type hints on an object's constructor.
```php
namespace Foo\Bar;

class Baz
{
  public function __construct(Qux $qux, Corge $corge)
  {
    $this->qux = $qux;
    $this->corge = $corge;
  }

  public function setQuux(Quux $quux)
  {
    $this->quux = $quux;
  }
}

$container->resolve('\Foo\Bar\Baz');
```
### Defining Arguments

Alternatively, you can specify what to inject into the class upon instantiation.

#### Define Constructor Args
```php
$container->bind('\Foo\Bar\Baz')->addArgs(array('\Foo\Bar\Quux', '\Foo\Bar\Corge'));

$container->resolve('\Foo\Bar\Baz');
```
#### Define Methods to Call with Args
```php
$container->bind('\Foo\Bar\Baz')->withMethod('setQuux', array('\Foo\Bar\Quux'));

$container->resolve('\Foo\Bar\Baz');
```
### Child Containers and Scope Resolution

A great feature of League\Di is it's ability to provide child containers with a separate resolution scope to that of it's parent container. If you bind a concrete class to an interface within one container, you can re-bind it in the child container, without fear of overwriting the original binding in the parent container.

#### Creating a Child Container

There are two ways to create a child container.
```php
$child = $continer->createChild();

// OR

$child = new Container($container);
```
#### Using a Child Container

The primary benefit of using child containers is scope-specific resolution.
```php
$container->bind('FooInterface', 'Foo');

// Assuming class Bar has a FooInterface dependency.
// This would use the Foo implementation.
$bar = $container->resolve('Bar');

// ...
$child = $container->createChild();
$child->bind('FooInterface', 'Baz');

// And this would use the Baz implementation.
$bar = $child->resolve('Bar');
```
## TODO

- Extensive Documentation


## Contributing

Please see [CONTRIBUTING](https://github.com/php-loep/di/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Don Gilbert](https://github.com/dongilbert)
- [All Contributors](https://github.com/php-loep/di/contributors)
- [Phil Bennett](https://twitter.com/philipobenito) / [Orno](http://getorno.com/)


## License

The MIT License (MIT). Please see [License File](https://github.com/php-loep/di/blob/master/LICENSE) for more information.
