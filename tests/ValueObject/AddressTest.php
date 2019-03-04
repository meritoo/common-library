<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\ValueObject;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\ValueObject\Address;

/**
 * Test case for the address
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class AddressTest extends BaseTestCase
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @var Address
     */
    private $addressWithoutFlat;

    /**
     * @var Address
     */
    private $addressWithoutStreet;

    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            Address::class,
            OopVisibilityType::IS_PUBLIC,
            5,
            4
        );
    }

    public function testGetFlatNumber()
    {
        static::assertSame('200', $this->address->getFlatNumber());
        static::assertSame('', $this->addressWithoutFlat->getFlatNumber());
        static::assertSame('300', $this->addressWithoutStreet->getFlatNumber());
    }

    public function testGetBuildingNumber()
    {
        static::assertSame('10', $this->address->getBuildingNumber());
        static::assertSame('22', $this->addressWithoutFlat->getBuildingNumber());
        static::assertSame('1', $this->addressWithoutStreet->getBuildingNumber());
    }

    public function testGetStreet()
    {
        static::assertSame('4th Avenue', $this->address->getStreet());
        static::assertSame('Green Street', $this->addressWithoutFlat->getStreet());
        static::assertSame('', $this->addressWithoutStreet->getStreet());
    }

    public function testGetFullStreet()
    {
        static::assertSame('4th Avenue 10/200', $this->address->getFullStreet());
        static::assertSame('Green Street 22', $this->addressWithoutFlat->getFullStreet());
        static::assertSame('', $this->addressWithoutStreet->getFullStreet());
    }

    public function testGetCity()
    {
        static::assertSame('New York', $this->address->getCity());
        static::assertSame('San Francisco', $this->addressWithoutFlat->getCity());
        static::assertSame('Saint Louis', $this->addressWithoutStreet->getCity());
    }

    public function testGetZipCode()
    {
        static::assertSame('00123', $this->address->getZipCode());
        static::assertSame('00456', $this->addressWithoutFlat->getZipCode());
        static::assertSame('00111', $this->addressWithoutStreet->getZipCode());
    }

    public function testToString()
    {
        static::assertSame('4th Avenue 10/200, 00123, New York', (string)$this->address);
        static::assertSame('Green Street 22, 00456, San Francisco', (string)$this->addressWithoutFlat);
        static::assertSame('00111, Saint Louis', (string)$this->addressWithoutStreet);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->address = new Address('New York', '00123', '4th Avenue', '10', '200');
        $this->addressWithoutFlat = new Address('San Francisco', '00456', 'Green Street', '22');
        $this->addressWithoutStreet = new Address('Saint Louis', '00111', '', '1', '300');
    }
}
