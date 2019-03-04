<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\Bundle;

use Meritoo\Common\Exception\Bundle\IncorrectBundleNameException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;

/**
 * Test case of an exception used while name of bundle is incorrect
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class IncorrectBundleNameExceptionTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            IncorrectBundleNameException::class,
            OopVisibilityType::IS_PUBLIC,
            3
        );
    }

    /**
     * @param string $description     Description of test
     * @param string $bundleName      Incorrect name of bundle
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider provideBundleNameAndMessage
     */
    public function testCreate($description, $bundleName, $expectedMessage)
    {
        $exception = IncorrectBundleNameException::create($bundleName);
        static::assertSame($expectedMessage, $exception->getMessage(), $description);
    }

    public function provideBundleNameAndMessage()
    {
        $template = 'Name of bundle \'%s\' is incorrect. It should start with big letter and end with "Bundle". Is'
            . ' there everything ok?';

        yield[
            'An empty string as name of bundle',
            '',
            sprintf($template, ''),
        ];

        yield[
            'Null as name of bundle',
            null,
            sprintf($template, ''),
        ];

        yield[
            'String with spaces as name of bundle',
            'This is test',
            sprintf($template, 'This is test'),
        ];

        yield[
            'String without spaces as name of bundle',
            'ThisIsTest',
            sprintf($template, 'ThisIsTest'),
        ];
    }
}
