# League\Di

[![Build Status](https://travis-ci.org/php-loep/di.png?branch=master)](https://travis-ci.org/php-loep/di)
[![Total Downloads](https://poser.pugx.org/league/di/downloads.png)](https://packagist.org/packages/league/di)
[![Latest Stable Version](https://poser.pugx.org/league/di/v/stable.png)](https://packagist.org/packages/league/di)

The League\Di library provides a fast and powerful Dependency Injection Container for your application.

## Install

Via Composer

    {
        "require": {
            "league/di": "1.*"
        }
    }


## Usage

### Get a Container Object

    include 'vendor/autoload.php';

    $container = new League\Di\Container;

### Bind a concrete class to an interface

    $container->bind('\Foo\Bar\BazInterface', '\Foo\Bar\Baz');

### Automatic Dependency Resolution

The Di Container is able to recursively resolve objects and their dependencies by inspecting the type hints on an object's constructor.

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

### Defining Arguments

Alternatively, you can specify what to inject into the class upon instantiation.

#### Define Constructor Args

    $container->bind('\Foo\Bar\Baz')->addArgs(array('\Foo\Bar\Quux', '\Foo\Bar\Corge'));

    $container->resolve('\Foo\Bar\Baz');

#### Define Methods to Call with Args

    $container->bind('\Foo\Bar\Baz')->withMethod('setQuux', array('\Foo\Bar\Quux'));

    $container->resolve('\Foo\Bar\Baz');


## TODO

- Extensive Documentation
- More Framework Integration


## Contributing

Please see [CONTRIBUTING](https://github.com/php-loep/di/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Don Gilbert](https://github.com/dongilbert)
- [All Contributors](https://github.com/php-loep/di/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/php-loep/di/blob/master/LICENSE) for more information.
