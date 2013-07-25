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
 * Container class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class Container
{
    /**
     * Array of container bindings.
     *
     * @var $array
     */
    protected $bindings = array();

    /**
     * Method to bind a concrete class to an abstract class or interface.
     *
     * @param string $abstract Class to bind.
     * @param mixed  $concrete Concrete definition to bind to $abstract.
     *                             Can be a \Closure or a string.
     *
     * @return mixed The concrete class for adding method calls / constructor arguments if desired.
     */
    public function bind($abstract, $concrete = null)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        if (is_string($concrete)) {
            $concrete = new Definition($this, $concrete);
        }

        $this->bindings[$abstract] = $concrete;

        return $concrete;
    }

    /**
     * Build a concrete instance of a class.
     *
     * @param string $concrete The name of the class to buld.
     *
     * @return mixed The instantiated class.
     */
    public function build($concrete)
    {
        $reflection = new \ReflectionClass($concrete);

        if (! $reflection->isInstantiable()) {
            throw new \InvalidArgumentException(sprintf('Class %s is not instantiable.', $concrete));
        }

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $this->getDependencies($constructor);

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * Recursively build the dependency list for the provided method.
     *
     * @param \ReflectionMethod $method The method for which to obtain dependencies.
     *
     * @return array An array containing the method dependencies.
     */
    protected function getDependencies(\ReflectionMethod $method)
    {
        $dependencies = array();

        foreach ($method->getParameters() as $param) {
            $dependency = $param->getClass();

            if (is_null($dependency)) {
                if ($param->isOptional()) {
                    $dependencies[] = $param->getDefaultValue();
                    continue;
                }
            } else {
                $dependencies[] = $this->resolve($dependency->name);
                continue;
            }

            throw new \InvalidArgumentException('Could not resolve ' . $param->getName());
        }

        return $dependencies;
    }

    /**
     * Resolve the given binding.
     *
     * @param string $binding The binding to resolve.
     *
     * @return mixed The results of invoking the binding callback.
     */
    public function resolve($binding)
    {
        // If the abstract is not registered, do it now for easy resolution.
        if (! isset($this->bindings[$binding])) {
            $this->bind($binding, $binding);
        }

        return $this->bindings[$binding]($this);
    }
}
