<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Arrays;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
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
