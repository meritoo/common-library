<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\ValueObject;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Address;
use Meritoo\Common\ValueObject\BankAccount;
use Meritoo\Common\ValueObject\Company;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Company::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(Address::class)]
#[UsesClass(BankAccount::class)]
#[UsesClass(Arrays::class)]
class CompanyTest extends BaseTestCase
{
    /**
     * @var Company
     */
    private $company;

    /**
     * @var Company
     */
    private $companyWithoutBankAccount;

    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            Company::class,
            OopVisibilityType::IS_PUBLIC,
            3,
            2
        );
    }

    public function testGetAddress()
    {
        static::assertEquals(
            new Address('New York', '00123', '4th Avenue', '10', '200'),
            $this->company->getAddress()
        );

        static::assertEquals(
            new Address('San Francisco', '00456', 'Green Street', '22'),
            $this->companyWithoutBankAccount->getAddress()
        );
    }

    public function testGetBankAccount()
    {
        static::assertEquals(
            new BankAccount('Bank 1', '12345'),
            $this->company->getBankAccount()
        );

        static::assertNull($this->companyWithoutBankAccount->getBankAccount());
    }

    public function testGetName()
    {
        static::assertSame('Test 1', $this->company->getName());
        static::assertSame('Test 2', $this->companyWithoutBankAccount->getName());
    }

    public function testToString()
    {
        static::assertSame('Test 1, 4th Avenue 10/200, 00123, New York, Bank 1, 12345', (string) $this->company);
        static::assertSame('Test 2, Green Street 22, 00456, San Francisco', (string) $this->companyWithoutBankAccount);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->company = new Company(
            'Test 1',
            new Address('New York', '00123', '4th Avenue', '10', '200'),
            new BankAccount('Bank 1', '12345')
        );

        $this->companyWithoutBankAccount = new Company(
            'Test 2',
            new Address('San Francisco', '00456', 'Green Street', '22')
        );
    }
}
