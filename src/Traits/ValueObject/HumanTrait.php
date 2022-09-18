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
    protected string $firstName;
    protected string $lastName;
    protected ?string $email;
    protected ?DateTime $birthDate;

    public function __construct(string $firstName, string $lastName, ?string $email = null, ?DateTime $birthDate = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->birthDate = $birthDate;
    }

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

    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

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

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
