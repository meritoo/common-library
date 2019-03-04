<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Reflection;

/**
 * The F class.
 * Used for testing the Reflection class.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class F
{
    protected $username;
    private $accountBalance;
    private $city;
    private $country;
    private $gInstance;

    public function __construct($accountBalance, $city, $country, $username, $firstName = 'John', $lastName = 'Scott')
    {
        $this->accountBalance = $accountBalance;
        $this->city = $city;
        $this->country = $country;
        $this->username = $username;
        $this->gInstance = new G($firstName, $lastName);

        /*
         * Called to avoid "Unused private method getAccountBalance" warning only
         */
        $this->getAccountBalance();
    }

    public function getCountry()
    {
        return $this->country;
    }

    protected function getCity()
    {
        return $this->city;
    }

    private function getAccountBalance()
    {
        return $this->accountBalance;
    }
}
