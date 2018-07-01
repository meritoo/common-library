<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities\Repository;

/**
 * Sortable object/entity.
 * Used for testing the Repository class.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Sortable
{
    /**
     * Position used while sorting
     *
     * @var int
     */
    private $position;

    /**
     * Class constructor
     *
     * @param int $position (optional) Position used while sorting
     */
    public function __construct($position = null)
    {
        $this->position = $position;
    }

    /**
     * Returns position used while sorting
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets position used while sorting
     *
     * @param int $position Position used while sorting
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Returns representation of object as string
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s (position: %d)', self::class, $this->getPosition());
    }
}
