<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Common\ValueObject;

use Meritoo\Common\Utilities\Arrays;

/**
 * Company
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Company
{
    /**
     * Name of company
     *
     * @var string
     */
    protected $name;

    /**
     * Address of company
     *
     * @var Address
     */
    protected $address;

    /**
     * Bank account of company
     *
     * @var BankAccount
     */
    protected $bankAccount;

    /**
     * Class constructor
     *
     * @param string           $name        Name of company
     * @param Address          $address     Address of company
     * @param null|BankAccount $bankAccount (optional) Bank account of company
     */
    public function __construct($name, Address $address, BankAccount $bankAccount = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->bankAccount = $bankAccount;
    }

    /**
     * Returns representation of object as string
     *
     * @return string
     */
    public function __toString()
    {
        $values = [
            $this->name,
            $this->address,
            $this->bankAccount,
        ];

        return Arrays::getNonEmptyValuesAsString($values);
    }

    /**
     * Returns name of company
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns address of company
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns bank account of company
     *
     * @return null|BankAccount
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }
}
