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
 * Corge Stub Test class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class Corge
{
    public $int;

    public function __construct($int = null)
    {
        $this->int = $int;
    }

    public function setInt($int)
    {
        $this->int = $int;
    }
}
