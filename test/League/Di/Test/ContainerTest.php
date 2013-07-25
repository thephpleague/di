<?php
/**
 * This file is part of the League\Di library.
 *
 * (c) Don Gilbert <don@dongilbert.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Di\Test;

use League\Di\Container;

/**
 * Container Test class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Setup procedure which runs before each test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->container = new Container;
    }

    /**
     * Tests binding a concrete to an abstract.
     *
     * @return void
     */
    public function testBind()
    {
        $this->container->bind('League\\Di\\Stub\\BarInterface', 'League\\Di\\Stub\\Bar');

        $bindings = $this->readAttribute($this->container, 'bindings');

        $this->assertArrayHasKey(
            'League\\Di\\Stub\\BarInterface',
            $bindings,
            'The key for the binding should equal the first argument passed.'
        );

        $definition = $bindings['League\\Di\\Stub\\BarInterface'];

        $this->assertInstanceOf(
            'League\\Di\\Definition',
            $definition,
            'Passing a string as the $concrete should result in an instance of Definition.'
        );

        $this->assertAttributeEquals(
            'League\\Di\\Stub\\Bar',
            'class',
            $definition,
            'The class attribute on the definition should be the $concrete param.'
        );
    }

    /**
     * Tests binding an abstract multiple times.
     *
     * @return void
     */
    public function testBindSameAbstractName()
    {
        $this->container->bind('foo', 'League\\Di\\Stub\\Baz');
        $this->container->bind('foo', 'League\\Di\\Stub\\Qux');

        $this->assertAttributeCount(
            1,
            'bindings',
            $this->container,
            'Binding the same abstract should overwrite an existing binding of the same name.'
        );
    }

    /**
     * Test binding a class, using the abstract as the concrete.
     *
     * @return void
     */
    public function testBindNoConcretePassed()
    {
        $this->container->bind('League\\Di\\Stub\\Baz');

        $bindings = $this->readAttribute($this->container, 'bindings');

        $this->assertArrayHasKey(
            'League\\Di\\Stub\\Baz',
            $bindings,
            'The key for the binding should equal the first argument passed.'
        );

        $definition = $bindings['League\\Di\\Stub\\Baz'];

        $this->assertInstanceOf(
            'League\\Di\\Definition',
            $definition,
            'Passing an empty $concrete should result in an instance of Definition.'
        );

        $this->assertAttributeEquals(
            'League\\Di\\Stub\\Baz',
            'class',
            $definition,
            'The class attribute on the created Definition should match the $abstract param if no $concrete is passed.'
        );
    }

    /**
     * Tests that a binding key has been bound.
     *
     * @return void
     */
    public function testBound()
    {
        $this->container->bind('foo', 'League\\Di\\Stub\\Foo');

        $this->assertTrue(
            $this->container->bound('foo'),
            'When checking a key that has been bound, this method should return true.'
        );
    }

    /**
     * Tests that a binding key has been bound.
     *
     * @return void
     */
    public function testNotBound()
    {
        $this->assertFalse(
            $this->container->bound('foo'),
            'When checking a key that has not been bound, this method should return false.'
        );
    }

    /**
     * Tests getting the dependencies of a class method.
     *
     * @return void
     */
    public function testGetDependencies()
    {
        $reflection = new \ReflectionMethod($this->container, 'getDependencies');
        $reflection->setAccessible(true);

        $bar = new \ReflectionClass('League\\Di\\Stub\\Bar');

        $constructor = $bar->getConstructor();

        $dependencies = $reflection->invoke($this->container, $constructor);

        $this->assertTrue(
            is_array($dependencies),
            'Dependencies should be returned as an array.'
        );

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Qux',
            $dependencies[0],
            'Dependencies should be resolved based on the parameter type hint.'
        );
    }

    /**
     * Tests getting dependency array from a class that has no dependencies.
     *
     * @return void
     */
    public function testGetDependenciesFromMethodWithoutDependencies()
    {
        $reflection = new \ReflectionMethod($this->container, 'getDependencies');
        $reflection->setAccessible(true);

        $bar = new \ReflectionClass('League\\Di\\Stub\\Baz');

        $method = $bar->getMethod('noDependencies');

        $dependencies = $reflection->invoke($this->container, $method);

        $this->assertEmpty(
            $dependencies,
            'A method without dependencies should return an empty array.'
        );
    }

    /**
     * Tests getting dependency array from a class that has no dependencies.
     *
     * @return void
     */
    public function testGetDependenciesFromMethodWithoutTypeHintsButWithDefaultValue()
    {
        $reflection = new \ReflectionMethod($this->container, 'getDependencies');
        $reflection->setAccessible(true);

        $bar = new \ReflectionClass('League\\Di\\Stub\\Baz');

        $method = $bar->getMethod('noTypeHint');

        $dependencies = $reflection->invoke($this->container, $method);

        $this->assertEquals(
            $dependencies[0],
            'baz',
            'A method without type hinted dependencies should return the default value, if available.'
        );
    }

    /**
     * Tests getting dependency array from a class that has no dependencies.
     *
     * @return void
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetDependenciesFromMethodWithoutTypeHintsAndWithoutDefaultValue()
    {
        $reflection = new \ReflectionMethod($this->container, 'getDependencies');
        $reflection->setAccessible(true);

        $bar = new \ReflectionClass('League\\Di\\Stub\\Baz');

        $method = $bar->getMethod('noTypeHintOrDefaultValue');

        $reflection->invoke($this->container, $method);
    }

    /**
     * Tests building a class that has no dependencies.
     *
     * @return void
     */
    public function testBuildClassNoDependencies()
    {
        $qux = $this->container->build('League\\Di\\Stub\\Qux');

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Qux',
            $qux,
            'Building a class that has not been bound should return an instance of the specified class.'
        );
    }

    /**
     * Tests building a class with a dependency declared in the constructor.
     *
     * @return void
     */
    public function testBuildClassWithConstructorDependency()
    {
        $bar = $this->container->build('League\\Di\\Stub\\Bar');

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Bar',
            $bar,
            'Building a class should return an instance of the specified class.'
        );

        $this->assertAttributeInstanceOf(
            'League\\Di\\Stub\\Qux',
            'qux',
            $bar,
            'A class with a constructor dependency should receive that dependency.'
        );
    }

    /**
     * Tests building a class that declares a dependency upon an interface in its constructor,
     * but that interface has not been bound within the container.
     *
     * @return void
     *
     * @expectedException \InvalidArgumentException
     */
    public function testBuildClassWithUnboundInterfaceDependency()
    {
        $this->container->build('League\\Di\\Stub\\Foo');
    }

    /**
     * Test building a class that declares a dependency opon an interface in its constructor.
     *
     * @return void
     */
    public function testBuildClassWithConstructorInterfaceDependency()
    {
        $this->container->bind('League\\Di\\Stub\\BarInterface', 'League\\Di\\Stub\\Bar');
        $this->container->bind('League\\Di\\Stub\\BazInterface', 'League\\Di\\Stub\\Baz');

        $foo = $this->container->build('League\\Di\\Stub\\Foo');

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Foo',
            $foo,
            'Building a class should return an instance of the specified class.'
        );

        $this->assertAttributeInstanceOf(
            'League\\Di\\Stub\\Bar',
            'bar',
            $foo,
            'A class declaring a dependency on an interface should use the resolved interface object from the container.'
        );

        $this->assertAttributeInstanceOf(
            'League\\Di\\Stub\\Baz',
            'baz',
            $foo,
            'A class declaring a dependency on an interface should use the resolved interface object from the container.'
        );
    }

    /**
     * Tests resolving a class which has been bound in the container.
     *
     * @return void
     */
    public function testResolveBound()
    {
        $this->container->bind('qux', 'League\\Di\\Stub\\Qux');

        $qux = $this->container->resolve('qux');

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Qux',
            $qux,
            'A class that has been bound should resolve to the bound definition.'
        );
    }

    /**
     * Tests resolving a class which has not been bound in the container.
     *
     * @return void
     */
    public function testResolveNotBound()
    {
        $bar = $this->container->resolve('League\\Di\\Stub\\Bar');

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Bar',
            $bar,
            'A class that has not been bound should still resolve to an instance of the requested class.'
        );

        $bindings = $this->readAttribute($this->container, 'bindings');

        $this->assertArrayHasKey(
            'League\\Di\\Stub\\Bar',
            $bindings,
            'A class that has not yet been bound should be bound prior to resolution.'
        );
    }
}
