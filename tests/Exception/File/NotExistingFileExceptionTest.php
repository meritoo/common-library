<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Exception\File;

use Generator;
use Meritoo\Common\Exception\File\NotExistingFileException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(NotExistingFileException::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(BaseTestCaseTrait::class)]
class NotExistingFileExceptionTest extends BaseTestCase
{
    /**
     * Provides path of not existing file and expected exception's message
     *
     * @return Generator
     */
    public static function providePathOfFile(): Generator
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

    #[DataProvider('providePathOfFile')]
    public function testConstructorMessage(string $notExistingFilePath, string $expectedMessage): void
    {
        $exception = NotExistingFileException::create($notExistingFilePath);
        static::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(NotExistingFileException::class, OopVisibilityType::IS_PUBLIC, 3);
    }
}
