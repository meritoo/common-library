<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Base;

use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * Base test case with common methods and data providers
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
abstract class BaseTestCase extends TestCase
{
    use BaseTestCaseTrait;
}
