<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Common\Exception\File;

use Generator;
use Meritoo\Common\Enums\OopVisibility;
use Meritoo\Common\Exception\File\EmptyFileException;
use Meritoo\Common\Test\Base\BaseTestCase;

/**
 * Test case of an exception used while file with given path is empty (has no content)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\File\EmptyFileException
 */
class EmptyFileExceptionTest extends BaseTestCase
{
    /**
     * Provides path of the empty file and expected exception's message
     *
     * @return Generator
     */
    public function providePathOfFile()
    {
        $template = 'File with path \'%s\' is empty (has no content). Did you provide path of proper file?';

        yield [
            'aa/bb/cc',
            sprintf($template, 'aa/bb/cc'),
        ];

        yield [
            'images/show/car.jpg',
            sprintf($template, 'images/show/car.jpg'),
        ];
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(EmptyFileException::class, OopVisibility::Public, 3);
    }

    /**
     * @param string $emptyFilePath   Path of the empty file
     * @param string $expectedMessage Expected exception's message
     *
     * @dataProvider providePathOfFile
     */
    public function testMessage($emptyFilePath, $expectedMessage)
    {
        $exception = EmptyFileException::create($emptyFilePath);
        static::assertSame($expectedMessage, $exception->getMessage());
    }
}
