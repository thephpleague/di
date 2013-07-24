<?php
/**
 * This file is part of the League\Di library.
 *
 * (c) Don Gilbert <don@dongilbert.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Di\Stub;

/**
 * Bar Stub Test class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class Bar implements BarInterface
{
	public $qux;

	public function __construct(Qux $qux)
	{
		$this->qux = $qux;
	}
}
