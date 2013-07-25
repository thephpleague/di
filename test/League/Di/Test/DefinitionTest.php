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
use League\Di\Definition;
use League\Di\Stub\Corge;

/**
 * Definition Test class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class DefinitionTest extends \PHPUnit_Framework_TestCase
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
     * Tests that the class properties are set correctly.
     *
     * @return void
     */
    public function testConstruct()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Qux');

        $this->assertAttributeInstanceOf(
            'League\\Di\\Container',
            'container',
            $definition,
            'The passed container name should be assigned to the $container property.'
        );

        $this->assertAttributeEquals(
            'League\\Di\\Stub\\Qux',
            'class',
            $definition,
            'The passed class name should be assigned to the $class property.'
        );
    }

    /**
     * Tests invoking a class with no args or method calls.
     *
     * @return void
     */
    public function testInvokeNoArgsOrMethodCalls()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Qux');

        $instance = $definition();

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Qux',
            $instance,
            'Invoking the Definition class should return an instance of the class $class.'
        );
    }

    /**
     * Tests invoking a class with defined args.
     *
     * @return void
     */
    public function testInvokeWithArgs()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Foo');

        $definition->addArg('League\\Di\\Stub\\Bar')->addArg('League\\Di\\Stub\\Baz');

        $instance = $definition();

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Foo',
            $instance,
            'Invoking a Definition should return an instance of the class defined in the $class property.'
        );

        $this->assertAttributeInstanceOf(
            'League\\Di\\Stub\\Bar',
            'bar',
            $instance,
            'Invoking a Definition with arguments assigned should pass those args to the method.'
        );

        $this->assertAttributeInstanceOf(
            'League\\Di\\Stub\\Baz',
            'baz',
            $instance,
            'Invoking a Definition with arguments assigned should pass those args to the method.'
        );
    }

    /**
     * Tests invoking a class with an integer as an args.
     *
     * @return void
     */
    public function testInvokeWithIntegerAsArg()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Corge');

        $definition->addArg(1);

        $instance = $definition();

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Corge',
            $instance,
            'Invoking a Definition should return an instance of the class defined in the $class property.'
        );

        $this->assertAttributeEquals(
            1,
            'int',
            $instance,
            'Invoking a Definition with arguments assigned should pass those args to the method.'
        );
    }

    /**
     * Tests invoking a class with a defined method call.
     *
     * @return void
     */
    public function testInvokeWithMethodCall()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Qux');

        $definition->withMethod('setBar', array('League\\Di\\Stub\\Bar'));

        $instance = $definition();

        $this->assertInstanceOf(
            'League\\Di\\Stub\\Qux',
            $instance,
            'Invoking a Definition should return an instance of the class defined in the $class property.'
        );

        $this->assertAttributeInstanceOf(
            'League\\Di\\Stub\\Bar',
            'bar',
            $instance,
            'Invoking a Definition with a defined method call pass the defined args to the method.'
        );
    }

    /**
     * Tests adding an argument to a Defintion.
     *
     * @return void
     */
    public function testAddArg()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Foo');

        $definition->addArg('foo');

        $this->assertAttributeContains(
            'foo',
            'arguments',
            $definition,
            'An added argument should be added to the arguments array.'
        );
    }

    /**
     * Tests adding an argument to a Defintion.
     *
     * @return void
     */
    public function testAddIntegerArg()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Foo');

        $definition->addArg(1);

        $args = $this->readAttribute($definition, 'arguments');

        $this->assertEquals(
            $args[0],
            1,
            'An added argument should be added to the arguments array, regardless of type'
        );
    }

    /**
     * Tests adding multiple arguments to a Defintion.
     *
     * @return void
     */
    public function testAddArgs()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Foo');

        $definition->addArgs(array('foo', 'bar'));

        $this->assertAttributeEquals(
            array('foo', 'bar'),
            'arguments',
            $definition,
            'Added arguments should be added to the arguments array.'
        );
    }

    public function testWithMethod()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Qux');

        $definition->withMethod('setBar', array('League\\Di\\Stub\\Bar'));

        $methods = $this->readAttribute($definition, 'methods');

        $this->assertArrayHasKey(
            'setBar',
            $methods,
            'Calling withMethod should set the defined method into the methods array.'
        );
    }

    public function testCallMethod()
    {
        $definition = new Definition($this->container, 'League\\Di\\Stub\\Corge');

        $definition->withMethod('setInt', array(1));

        $reflection = new \ReflectionMethod($definition, 'callMethods');
        $reflection->setAccessible(true);

        $object = new Corge;

        $objectWithMethodsCalled = $reflection->invoke($definition, $object);

        $this->assertAttributeEquals(
            1,
            'int',
            $objectWithMethodsCalled,
            'Running callMethod on a given object should call the method and pass the args.'
        );
    }
}
