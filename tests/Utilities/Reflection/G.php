<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities\Reflection;

/**
 * The G class.
 * Used for testing the Reflection class.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class G
{
    private $firstName;
    private $lastName;

    public function __construct($firstName = 'John', $lastName = 'Scott')
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }
}
