<?php
/**
 * This file is part of the League\Di library.
 *
 * (c) Don Gilbert <don@dongilbert.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Di;

/**
 * Definition class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class Definition
{
	/**
	 * Array of arguments to pass to the class constructor
	 *
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * The class name for this Definition.
	 *
	 * @var string
	 */
	protected $class;

	/**
	 * The holding container.
	 *
	 * @var League\Di\Container
	 */
	protected $container;

	/**
	 * Method to call on the newly created object for injection.
	 *
	 * @var array
	 */
	protected $methods = array();

	/**
	 * Constructor
	 *
	 * @param League\Di\Container $container
	 * @param string              $class
	 */
	public function __construct(Container $container, $class)
	{
		$this->container = $container;
		$this->class = $class;
	}

	/**
	 * Magic method. Runs when using this class as a function.
	 * Ex: $object = new Definition($container, $class);
	 *     $invoked = $object();
	 *
	 * @return object The instantiated $class with optional args passed to the constructor and methods called.
	 */
	public function __invoke()
	{
		if (empty($this->arguments))
		{
			if (empty($this->methods))
			{
				$object = $this->container->build($this->class);
			}
			else
			{
				$object = new $this->class;
			}
		}
		else
		{
			$reflection = new \ReflectionClass($this->class);

			$arguments = array();

			foreach ($this->arguments as $arg)
			{
				if (is_string($arg) && (class_exists($arg) || $this->container->bound($arg)))
				{
					$arguments[] = $this->container->build($arg);
					continue;
				}

				$arguments[] = $arg;
			}

			$object = $reflection->newInstanceArgs($arguments);
		}

		return $this->callMethods($object);
	}

	/**
	 * Add an argument to the class's constructor.
	 *
	 * @param   string  $arg  The argument to add. Can be a class name.
	 *
	 * @return  League\Di\Definition
	 */
	public function addArg($arg)
	{
		$this->arguments[] = $this->container->resolve($arg);

		return $this;
	}

	/**
	 * Add multiple arguments to the class's constructor.
	 *
	 * @param   array  $arg  An array of arguments.
	 *
	 * @return  League\Di\Definition
	 */
	public function addArgs(array $args)
	{
		foreach ($args as $arg)
		{
			$this->arguments[] = $this->container->resolve($arg);
		}

		return $this;
	}

	/**
	 * Adds a method call to be executed after instantiating. 
	 *
	 * @param   string  $method  The method name to call.
	 * @param   array   $args    Array of arguments to pass to the call.
	 *
	 * @return  League\Di\Definition
	 */
	public function withMethod($method, array $args = array())
	{
		$this->methods[$method] = $args;

		return $this;
	}

	/**
	 * Execute the methods added via call()
	 *
	 * @param   object  $object  The instatiated $class on which to call the methods.
	 *
	 * @return  The created object
	 */
	protected function callMethods($object)
	{
		if (! empty($this->methods))
		{
			foreach ($this->methods as $method => $args)
			{
				$reflection = new \ReflectionMethod($object, $method);

				$arguments = array();

				foreach ($args as $arg)
				{
					if (is_string($arg))
					{
						$arguments[] = $this->container->resolve($arg);
						continue;
					}

					$arguments[] = $arg;
				}

				$reflection->invokeArgs($object, $arguments);
			}
		}

		return $object;
	}
}
