<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    protected string $name;
    protected Address $address;
    protected ?BankAccount $bankAccount;

    public function __construct($name, Address $address, ?BankAccount $bankAccount = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->bankAccount = $bankAccount;
    }

    public function __toString(): string
    {
        $values = [
            $this->name,
            $this->address,
            $this->bankAccount,
        ];

        return Arrays::getNonEmptyValuesAsString($values);
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
