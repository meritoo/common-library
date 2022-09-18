<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\ValueObject;

use Meritoo\Common\Utilities\Arrays;

/**
 * Bank account
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class BankAccount
{
    protected string $bankName;
    protected string $accountNumber;

    public function __construct(string $bankName, string $accountNumber)
    {
        $this->bankName = $bankName;
        $this->accountNumber = $accountNumber;
    }

    public function __toString(): string
    {
        $values = [
            $this->bankName,
            $this->accountNumber,
        ];

        return Arrays::getNonEmptyValuesAsString($values);
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getBankName(): string
    {
        return $this->bankName;
    }
}
