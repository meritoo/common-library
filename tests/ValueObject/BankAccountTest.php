<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\ValueObject;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\ValueObject\BankAccount;

/**
 * Test case for the bank account
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class BankAccountTest extends BaseTestCase
{
    /**
     * @var BankAccount
     */
    private $emptyBankAccount;

    /**
     * @var BankAccount
     */
    private $bankAccount;

    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            BankAccount::class,
            OopVisibilityType::IS_PUBLIC,
            2,
            2
        );
    }

    public function testGetAccountNumber()
    {
        self::assertSame('', $this->emptyBankAccount->getAccountNumber());
        self::assertSame('1234567890', $this->bankAccount->getAccountNumber());
    }

    public function testGetBankName()
    {
        self::assertSame('', $this->emptyBankAccount->getBankName());
        self::assertSame('Bank of America', $this->bankAccount->getBankName());
    }

    public function testToString()
    {
        static::assertSame('', (string)$this->emptyBankAccount);
        static::assertSame('Bank of America, 1234567890', (string)$this->bankAccount);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->emptyBankAccount = new BankAccount('', '');
        $this->bankAccount = new BankAccount('Bank of America', '1234567890');
    }
}
