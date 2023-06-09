<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\ValueObject;

use DateTime;
use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Traits\Test\Base\BaseTypeTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Human;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Human::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(BaseTypeTestCaseTrait::class)]
#[UsesClass(Reflection::class)]
class HumanTest extends BaseTestCase
{
    private Human $emptyHuman;
    private Human $human;

    public static function provideHuman(): Generator
    {
        yield [
            'Without any data (an empty human)',
            new Human('', ''),
            '',
        ];

        yield [
            'With first and last name only',
            new Human('John', 'Scott'),
            'John Scott',
        ];

        yield [
            'With first name, last name and email',
            new Human('John', 'Scott', 'john@scott.com'),
            'John Scott <john@scott.com>',
        ];

        yield [
            'With whole/complete data',
            new Human('John', 'Scott', 'john@scott.com', new DateTime('2001-01-01')),
            'John Scott <john@scott.com>',
        ];
    }

    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            Human::class,
            OopVisibilityType::IS_PUBLIC,
            4,
            2
        );
    }

    public function testGetBirthDate()
    {
        static::assertNull($this->emptyHuman->getBirthDate());
        static::assertEquals(new DateTime('2001-01-01'), $this->human->getBirthDate());
    }

    public function testGetEmail()
    {
        static::assertNull($this->emptyHuman->getEmail());
        static::assertSame('john@scott.com', $this->human->getEmail());
    }

    public function testGetFirstName()
    {
        static::assertSame('', $this->emptyHuman->getFirstName());

        static::assertSame('John', $this->human->getFirstName());
    }

    public function testGetFullName()
    {
        static::assertSame('', $this->emptyHuman->getFullName());
        static::assertSame('John Scott', $this->human->getFullName());
    }

    public function testGetLastName()
    {
        static::assertSame('', $this->emptyHuman->getLastName());
        static::assertSame('Scott', $this->human->getLastName());
    }

    /**
     * @param string $description Description of test
     * @param Human  $human       Human to verify
     * @param string $expected    Expected string
     *
     * @dataProvider provideHuman
     */
    public function testToString($description, Human $human, $expected)
    {
        static::assertSame($expected, (string) $human, $description);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->emptyHuman = new Human('', '');
        $this->human = new Human('John', 'Scott', 'john@scott.com', new DateTime('2001-01-01'));
    }
}
