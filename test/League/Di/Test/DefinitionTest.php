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
	 * Tests invoking a class with no args or method calls.
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

    public function testAddArg()
    {
        $this->markTestIncomplete('This test has not yet been implemented.');
    }

    public function testAddArgs()
    {
        $this->markTestIncomplete('This test has not yet been implemented.');
    }

    public function testWithMethod()
    {
        $this->markTestIncomplete('This test has not yet been implemented.');
    }

    public function testCallMethod()
    {
        $this->markTestIncomplete('This test has not yet been implemented.');
    }
}
