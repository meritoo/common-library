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
    /**
     * Name of bank
     *
     * @var string
     */
    protected $bankName;

    /**
     * Number of bank's account
     *
     * @var string
     */
    protected $accountNumber;

    /**
     * Class constructor
     *
     * @param string $bankName      Name of bank
     * @param string $accountNumber Number of bank's account
     */
    public function __construct($bankName, $accountNumber)
    {
        $this->bankName = $bankName;
        $this->accountNumber = $accountNumber;
    }

    /**
     * Returns representation of object as string
     *
     * @return string
     */
    public function __toString()
    {
        $values = [
            $this->bankName,
            $this->accountNumber,
        ];

        return Arrays::getNonEmptyValuesAsString($values);
    }

    /**
     * Returns name of bank
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Returns number of bank's account
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }
}
