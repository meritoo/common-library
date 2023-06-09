<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Test\Base;

use DateTime;
use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\Base\BaseType;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\GeneratorUtility;
use Meritoo\Common\Utilities\Miscellaneous;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\Utilities\Regex;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(BaseTestCase::class)]
#[UsesClass(BaseType::class)]
#[UsesClass(Reflection::class)]
#[UsesClass(GeneratorUtility::class)]
#[UsesClass(Arrays::class)]
#[UsesClass(Miscellaneous::class)]
#[UsesClass(Regex::class)]
class BaseTestCaseTest extends BaseTestCase
{
    private SimpleTestCase $instance;

    /**
     * Provides name of file and path of directory containing the file
     *
     * @return Generator
     */
    public static function provideFileNameAndDirectoryPath(): Generator
    {
        yield [
            'abc.jpg',
            '',
        ];

        yield [
            'abc.def.jpg',
            '',
        ];

        yield [
            'abc.jpg',
            'def',
        ];

        yield [
            'abc.def.jpg',
            'def',
        ];
    }

    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(
            BaseTestCase::class,
            OopVisibilityType::IS_PUBLIC,
            1,
            1
        );
    }

    /**
     * @param string $fileName      Name of file
     * @param string $directoryPath Path of directory containing the file
     *
     * @dataProvider provideFileNameAndDirectoryPath
     */
    public function testGetFilePathForTesting($fileName, $directoryPath)
    {
        $path = BaseTestCase::getFilePathForTesting($fileName, $directoryPath);

        if (!empty($directoryPath)) {
            $directoryPath .= '/';
        }

        $expectedContains = sprintf('/data/tests/%s%s', $directoryPath, $fileName);
        static::assertStringContainsString($expectedContains, $path);
    }

    public function testProvideBooleanValue()
    {
        $elements = [
            [false],
            [true],
        ];

        $generator = $this->instance->provideBooleanValue();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));
    }

    public function testProvideDateTimeInstance()
    {
        $dateFormat = 'Y-m-d H:i';

        $expectedElements = [
            [new DateTime()],
            [new DateTime('yesterday')],
            [new DateTime('now')],
            [new DateTime('tomorrow')],
        ];

        $generator = $this->instance->provideDateTimeInstance();
        $generatedElements = GeneratorUtility::getGeneratorElements($generator);

        /** @var DateTime $instance1 */
        $instance1 = $generatedElements[0][0];

        /** @var DateTime $instance2 */
        $instance2 = $generatedElements[1][0];

        /** @var DateTime $instance3 */
        $instance3 = $generatedElements[2][0];

        /** @var DateTime $instance4 */
        $instance4 = $generatedElements[3][0];

        self::assertCount(count($expectedElements), $generatedElements);
        self::assertEquals($instance1->format($dateFormat), (new DateTime())->format($dateFormat));
        self::assertEquals($instance2->format($dateFormat), (new DateTime('yesterday'))->format($dateFormat));
        self::assertEquals($instance3->format($dateFormat), (new DateTime('now'))->format($dateFormat));
        self::assertEquals($instance4->format($dateFormat), (new DateTime('tomorrow'))->format($dateFormat));
    }

    public function testProvideDateTimeRelativeFormat()
    {
        $elements = [
            ['now'],
            ['yesterday'],
            ['tomorrow'],
            ['back of 10'],
            ['front of 10'],
            ['last day of February'],
            ['first day of next month'],
            ['last day of previous month'],
            ['last day of next month'],
            ['Y-m-d'],
            ['Y-m-d 10:00'],
        ];

        $generator = $this->instance->provideDateTimeRelativeFormat();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));
    }

    public function testProvideEmptyValue()
    {
        $elements = [
            [''],
            ['   '],
            ['0'],
            [0],
            [false],
            [null],
            [[]],
        ];

        $generator = $this->instance->provideEmptyValue();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));
    }

    public function testProvideNotExistingFilePath()
    {
        $elements = [
            ['lets-test.doc'],
            ['lorem/ipsum.jpg'],
            ['surprise/me/one/more/time.txt'],
        ];

        $generator = $this->instance->provideNotExistingFilePath();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new SimpleTestCase('simple_test_case');
    }
}

/**
 * Simple test case
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class SimpleTestCase extends BaseTestCase
{
}
