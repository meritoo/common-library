<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities\Reflection;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class F
{
    protected $username;
    private $accountBalance;
    private $city;
    private $country;
    private $g;

    public function __construct($accountBalance, $city, $country, $username, $firstName = 'John', $lastName = 'Scott')
    {
        $this->accountBalance = $accountBalance;
        $this->city = $city;
        $this->country = $country;
        $this->username = $username;
        $this->g = new G($firstName, $lastName);

        // Called to avoid "Unused private method getAccountBalance" warning only
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
