# League\Di

The League\Di library provides a simple IoC Container for your application. Dependency Injection allows you the developer to control the construction and lifecycle of your objects, rather than leaving that control to the classes themselves. Instead of hard coding a class's dependencies within the class `__construct()` method, you instead provide to a class the dependencies it requires as arguments to its constructor. This helps to decrease hard dependencies and to create loosely coupled code.

An Dependency Injection Container helps you to manage these dependencies in a controlled fashion.

## Install

Via Composer

    {
        "require": {
            "league/di": ">=0.1"
        }
    }


## Usage

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

### Define Arguments

Alternatively, you can specify what to inject into the class upon instantiation.

#### Define Constructor Args

    $container->bind('\Foo\Bar\Baz')->addArgs(array('\Foo\Bar\Quux', '\Foo\Bar\Corge'));

    $container->resolve('\Foo\Bar\Baz');

#### Define Method Calls & Args

    $container->bind('\Foo\Bar\Baz')->withMethod('setQuux', array('\Foo\Bar\Quux'));

    $container->resolve('\Foo\Bar\Baz');


## TODO

- Full Unit Test Coverage
- Extensive Documentation
- More Framework Integration


## Contributing

Please see [CONTRIBUTING](https://github.com/dongilbert/loep-di/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Don Gilbert](https://github.com/dongilbert)
- [All Contributors](https://github.com/dongilbert/loep-di/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/php-loep/statsd/blob/master/LICENSE) for more information.
