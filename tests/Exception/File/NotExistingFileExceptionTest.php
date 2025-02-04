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
use Meritoo\Common\Exception\File\NotExistingFileException;
use Meritoo\Common\Test\Base\BaseTestCase;

/**
 * Test case of an exception used while file with given path does not exist
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Common\Exception\File\NotExistingFileException
 */
class NotExistingFileExceptionTest extends BaseTestCase
{
    /**
     * Provides path of not existing file and expected exception's message
     *
     * @return Generator
     */
    public function providePathOfFile()
    {
        $template = 'File with path \'%s\' does not exist (or is not readable). Did you provide path of proper file?';

        yield [
            'aa/bb/cc',
            sprintf($template, 'aa/bb/cc'),
        ];

        yield [
            'images/show/car.jpg',
            sprintf($template, 'images/show/car.jpg'),
        ];
    }

    /**
     * @param string $notExistingFilePath Path of not existing (or not readable) file
     * @param string $expectedMessage     Expected exception's message
     *
     * @dataProvider providePathOfFile
     */
    public function testConstructorMessage($notExistingFilePath, $expectedMessage)
    {
        $exception = NotExistingFileException::create($notExistingFilePath);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments()
    {
        static::assertConstructorVisibilityAndArguments(NotExistingFileException::class, OopVisibility::Public, 3);
    }
}
