<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Traits\Test\Base;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTypeTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Test\Common\Traits\Test\Base\BaseTypeTestCaseTrait\SimpleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BaseTypeTestCaseTrait::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
class BaseTypeTestCaseTraitTest extends BaseTestCase
{
    private SimpleTestCase $instance;

    public function testProvideTypeToVerify(): void
    {
        foreach ($this->instance->provideTypeToVerify() as $value) {
            self::assertIsString($value[0]);
        }
    }

    public function testTestAvailabilityOfAllTypes(): void
    {
        $this->instance->testAvailabilityOfAllTypes();
    }

    public function testTestIfGivenTypeIsCorrect(): void
    {
        $this->instance->testIfGivenTypeIsCorrect(true, true);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new SimpleTestCase('simple_test_case');
    }
}
