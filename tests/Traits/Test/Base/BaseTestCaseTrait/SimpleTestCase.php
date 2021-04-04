<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Traits\Test\Base\BaseTestCaseTrait;

use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;

/**
 * @internal
 * @coversNothing
 */
class SimpleTestCase
{
    use BaseTestCaseTrait;

    private function thePrivateMethod(): void
    {
    }
}
