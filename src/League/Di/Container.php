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
     * The parent Container object.
     *
     * @var Container
     */
    protected $parent;

    /**
     * Constructor
     *
     * @param object $parent Container
     */
    public function __construct(Container $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Create a child Container with a new property scope that
     * that has the ability to access the parent scope when resolving.
     *
     * @return Container
     */
    public function createChild()
    {
        return new static($this);
    }

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
     * Checks to see if a binding key has been bound in current Container.
     * If not bound in the current Container, it will recursively search
     * parent Container's until it finds the $binding.
     *
     * @param string $binding The binding to check.
     */
    public function bound($binding)
    {
        return isset($this->bindings[$binding]) || (isset($this->parent) && $this->parent->bound($binding));
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
     * Extend an existing binding.
     *
     * @param   string   $binding  The name of the binding to extend.
     * @param   Closure  $closure  The function to use to extend the existing binding.
     *
     * @return  void
     */
    public function extend($binding, \Closure $closure)
    {
        $rawObject = $this->getRaw($binding);

        if (is_null($rawObject)) {
            throw new \InvalidArgumentException(sprintf('Cannot extend %s because it has not yet been bound.', $binding));
        }

        $this->bind(
            $binding,
            function ($container) use ($closure, $rawObject) {
                return $closure($container, $rawObject($container));
            }
        );
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
     * Get the raw object prior to resolution.
     *
     * @param string $binding The $binding key to get the raw value from.
     *
     * @return mixed Value of the $binding.
     */
    public function getRaw($binding)
    {
        if (isset($this->bindings[$binding])) {
            return $this->bindings[$binding];
        } elseif (isset($this->parent)) {
            return $this->parent->getRaw($binding);
        }

        return null;
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
        $rawObject = $this->getRaw($binding);

        // If the abstract is not registered, do it now for easy resolution.
        if (is_null($rawObject)) {
            // Pass $binding to both so it doesn't need to check if null again.
            $this->bind($binding, $binding);

            $rawObject = $this->getRaw($binding);
        }

        return $rawObject($this);
    }
}
