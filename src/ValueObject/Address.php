<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\ValueObject;

use Meritoo\Common\Utilities\Arrays;

/**
 * Address
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Address
{
    protected string $street;
    protected string $buildingNumber;
    protected string $flatNumber;
    protected string $zipCode;
    protected string $city;

    public function __construct(
        string $city,
        string $zipCode,
        string $street,
        string $buildingNumber,
        string $flatNumber = ''
    ) {
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->flatNumber = $flatNumber;
    }

    public function __toString(): string
    {
        $values = [
            $this->getFullStreet(),
            $this->zipCode,
            $this->city,
        ];

        return Arrays::getNonEmptyValuesAsString($values);
    }

    public function getBuildingNumber(): string
    {
        return $this->buildingNumber;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getFlatNumber(): string
    {
        return $this->flatNumber;
    }

    public function getFullStreet(): string
    {
        if (empty($this->street)) {
            return '';
        }

        $numbers = $this->buildingNumber;

        if (!empty($numbers) && !empty($this->flatNumber)) {
            $numbers = sprintf('%s/%s', $numbers, $this->flatNumber);
        }

        return sprintf('%s %s', $this->street, $numbers);
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }
}
