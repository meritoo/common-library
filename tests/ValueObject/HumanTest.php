<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\ValueObject;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\ValueObject\Human;

/**
 * Test case for the human
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class HumanTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            Human::class,
            OopVisibilityType::IS_PUBLIC,
            4,
            2
        );
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
        static::assertSame($expected, (string)$human, $description);
    }

    public function testGetFirstName()
    {
        $empty = new Human('', '');
        static::assertSame('', $empty->getFirstName());

        $human = new Human('John', 'Scott');
        static::assertSame('John', $human->getFirstName());
    }

    public function testGetLastName()
    {
        $empty = new Human('', '');
        static::assertSame('', $empty->getLastName());

        $human = new Human('John', 'Scott');
        static::assertSame('Scott', $human->getLastName());
    }

    public function testGetBirthDate()
    {
        $empty = new Human('', '');
        static::assertNull($empty->getBirthDate());

        $human = new Human('John', 'Scott', '', new \DateTime('2001-01-01'));
        static::assertEquals(new \DateTime('2001-01-01'), $human->getBirthDate());
    }

    public function testGetFullName()
    {
        $empty = new Human('', '');
        static::assertSame('', $empty->getFullName());

        $human = new Human('John', 'Scott', '', new \DateTime('2001-01-01'));
        static::assertSame('John Scott', $human->getFullName());
    }

    public function testGetEmail()
    {
        $empty = new Human('', '');
        static::assertNull($empty->getEmail());

        $human = new Human('John', 'Scott', 'john@scott.com');
        static::assertSame('john@scott.com', $human->getEmail());
    }

    public function provideHuman()
    {
        yield[
            'Without any data (an empty human)',
            new Human('', ''),
            '',
        ];

        yield[
            'With first and last name only',
            new Human('John', 'Scott'),
            'John Scott',
        ];

        yield[
            'With first name, last name and email',
            new Human('John', 'Scott', 'john@scott.com'),
            'John Scott <john@scott.com>',
        ];

        yield[
            'With whole/complete data',
            new Human('John', 'Scott', 'john@scott.com', new \DateTime('2001-01-01')),
            'John Scott <john@scott.com>',
        ];
    }
}
