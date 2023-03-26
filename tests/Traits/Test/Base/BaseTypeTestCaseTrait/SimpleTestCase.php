<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Traits\Test\Base\BaseTypeTestCaseTrait;

use Generator;
use Meritoo\Common\Traits\Test\Base\BaseTypeTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SimpleTestCase extends TestCase
{
    use BaseTypeTestCaseTrait;

    public function provideTypeToVerify(): Generator
    {
        yield [
            '',
            true,
        ];

        yield [
            'abc',
            true,
        ];
    }

    protected function getAllExpectedTypes(): array
    {
        return [
            'A' => 'a',
            'B' => 'b',
        ];
    }

    protected function getTestedTypeInstance(): BaseType
    {
        return new TestedType();
    }
}
