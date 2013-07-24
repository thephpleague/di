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
use League\Di\Stub;

/**
 * Container Test class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
	protected $container;

	public function setUp()
	{
		$this->container = new Container;
	}

	public function testBind()
	{
		$this->markTestIncomplete('This test has not yet been implemented.');
	}

	public function testBuild()
	{
		$this->markTestIncomplete('This test has not yet been implemented.');
	}

	public function testGetSingleDependency()
	{
		$this->markTestIncomplete('This test has not yet been implemented.');
	}

	public function testGetMultipleDependencies()
	{
		$this->markTestIncomplete('This test has not yet been implemented.');
	}

	public function testResolveBound()
	{
		$this->markTestIncomplete('This test has not yet been implemented.');
	}

	public function testResolveNotBound()
	{
		$this->markTestIncomplete('This test has not yet been implemented.');
	}
}
