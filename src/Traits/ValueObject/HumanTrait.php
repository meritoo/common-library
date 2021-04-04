<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\Traits\ValueObject;

use DateTime;

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
     * @var null|string
     */
    protected $email;

    /**
     * Birth date
     *
     * @var null|DateTime
     */
    protected $birthDate;

    /**
     * Class constructor
     *
     * @param string        $firstName First name
     * @param string        $lastName  Last name
     * @param null|string   $email     (optional) Email address. Default: null.
     * @param null|DateTime $birthDate (optional) Birth date. Default: null.
     */
    public function __construct(string $firstName, string $lastName, ?string $email = null, ?DateTime $birthDate = null)
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
        $email = '';

        if ('' !== $this->email && null !== $this->email) {
            $template .= ' <%s>';
            $email = $this->email;
        }

        return sprintf($template, $this->getFullName(), $email);
    }

    /**
     * Returns first name
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Returns last name
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Returns email address
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Returns birth date
     *
     * @return null|DateTime
     */
    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    /**
     * Returns the full name
     *
     * @param bool $firstNameFirst (optional) If is set to true, first name is the first part (default behaviour).
     *                             Otherwise - name.
     * @return string
     */
    public function getFullName(bool $firstNameFirst = true): string
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
