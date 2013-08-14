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
class ContainerAwareInterface
{
    /**
     * Get the DI container.
     *
     * @return  Di\Container
     *
     * @since   __DEPLOY_VERSION__
     * @throws  \UnexpectedValueException May be thrown if the container has not been set.
     */
    public function getContainer();

    /**
     * Set the DI container.
     *
     * @param   Di\Container  $container  The DI container.
     *
     * @return  Controller  Returns itself to support chaining.
     *
     * @since   __DEPLOY_VERSION__
     */
	public function setContainer(Di\Container $container);
}
