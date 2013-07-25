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
 * Baz Stub Test class
 *
 * @author  Don Gilbert <don@dongilbert.net>
 */
class Baz implements BazInterface
{
    public function noDependencies()
    {
        return true;
    }

    public function noTypeHint($arg = 'baz')
    {
        return $arg;
    }

    public function noTypeHintOrDefaultValue($arg)
    {
        return $arg;
    }
}
