# League\Di

The League\Di library provides a simple IoC Container for your application. Dependency Injection allows you the developer to control the construction and lifecycle of your objects, rather than leaving that control to the classes themselves. Instead of hard coding a class's dependencies within the class `__construct()` method, you instead provide to a class the dependencies it requires as arguments to its constructor. This helps to decrease hard dependencies and to create loosely coupled code.

An Dependency Injection Container helps you to manage these dependencies in a controlled fashion.

## Automatic Dependency Resolution

The Di Container is able to recursively resolve objects and their dependencies by inspecting the type hints on an object's constructor.

```php

include 'vendor/autoload.php';

class Foo
{
    public $bar;
    public $baz;

    public function __construct(Bar $bar, Baz $baz)
    {
        $this->bar = $bar;
        $this->baz = $baz;
    }
}

class Bar
{
    public $qux;

    public function __construct(Qux $qux)
    {
        $this->qux = $qux;
    }
}

class Baz {}

class Qux {}

$container = new League\Di\Container;

var_dump($container->resolve('Foo'));
```
Running the above will give you the following result:

```
class Foo#5 (2) {
  public $bar =>
  class Bar#9 (1) {
    public $qux =>
    class Qux#13 (0) {
    }
  }
  public $baz =>
  class Baz#14 (0) {
  }
}

```
