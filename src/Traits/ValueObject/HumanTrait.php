<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\ValueObject;

/**
 * Methods and properties related to human
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait HumanTrait
{
    /**
     * First name
     *
     * @var string
     */
    protected $firstName;

    /**
     * Last name
     *
     * @var string
     */
    protected $lastName;

    /**
     * Email address
     *
     * @var string
     */
    protected $email;

    /**
     * Birth date
     *
     * @var \DateTime
     */
    protected $birthDate;

    /**
     * Class constructor
     *
     * @param string    $firstName First name
     * @param string    $lastName  Last name
     * @param string    $email     (optional) Email address
     * @param \DateTime $birthDate (optional) Birth date
     */
    public function __construct($firstName, $lastName, $email = null, \DateTime $birthDate = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->birthDate = $birthDate;
    }

    /**
     * Returns representation of object as string
     *
     * @return string
     */
    public function __toString()
    {
        $template = '%s';

        if ('' !== $this->email && null !== $this->email) {
            $template .= ' <%s>';
        }

        return sprintf($template, $this->getFullName(), $this->email);
    }

    /**
     * Returns first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Returns last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Returns email address
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns birth date
     *
     * @return \DateTime|null
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Returns the full name
     *
     * @param bool $firstNameFirst (optional) If is set to true, first name is the first part. Otherwise - last name.
     * @return string
     */
    public function getFullName($firstNameFirst = true)
    {
        $beginning = $this->lastName;
        $finish = $this->firstName;

        if ($firstNameFirst) {
            $beginning = $this->firstName;
            $finish = $this->lastName;
        }

        return trim(sprintf('%s %s', $beginning, $finish));
    }
}
