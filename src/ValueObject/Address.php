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
    /**
     * The street
     *
     * @var string
     */
    protected $street;

    /**
     * The number of building
     *
     * @var string
     */
    protected $buildingNumber;

    /**
     * The number of flat
     *
     * @var string
     */
    protected $flatNumber;

    /**
     * The zip code
     *
     * @var string
     */
    protected $zipCode;

    /**
     * The city, location
     *
     * @var string
     */
    protected $city;

    /**
     * Class constructor
     *
     * @param string $city           City, location
     * @param string $zipCode        The zip code
     * @param string $street         The street
     * @param string $buildingNumber The number of building
     * @param string $flatNumber     (optional) The number of flat. Default: "".
     */
    public function __construct($city, $zipCode, $street, $buildingNumber, $flatNumber = '')
    {
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->flatNumber = $flatNumber;
    }

    /**
     * Returns representation of object as string
     *
     * @return string
     */
    public function __toString()
    {
        $values = [
            $this->getFullStreet(),
            $this->zipCode,
            $this->city,
        ];

        return Arrays::getNonEmptyValuesAsString($values);
    }

    /**
     * Returns street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Returns full street (name + building & flat number)
     *
     * @return string
     */
    public function getFullStreet()
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

    /**
     * Returns number of building
     *
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->buildingNumber;
    }

    /**
     * Returns number of flat
     *
     * @return string
     */
    public function getFlatNumber()
    {
        return $this->flatNumber;
    }

    /**
     * Returns zip code
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Returns city, location
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
}
