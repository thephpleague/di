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
 * Foo Stub Test class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class Foo
{
	public $bar;
	public $baz;

	public function __construct(BarInterface $bar, BazInterface $baz)
	{
		$this->bar = $bar;
		$this->baz = $baz;
	}
}
