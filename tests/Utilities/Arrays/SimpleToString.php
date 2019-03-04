<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Arrays;

/**
 * Simple class convertible to string.
 * Used for testing the Arrays class.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class SimpleToString
{
    /**
     * Identifier
     *
     * @var string
     */
    private $id;

    /**
     * Class constructor
     *
     * @param string $id Identifier
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Returns representation of object as string
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('Instance with ID: %s', $this->id);
    }
}
