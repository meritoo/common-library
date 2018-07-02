<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Test\Base;

use DateTime;
use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\GeneratorUtility;

/**
 * Test case of the base test case with common methods and data providers
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class BaseTestCaseTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertConstructorVisibilityAndArguments(BaseTestCase::class, OopVisibilityType::IS_PUBLIC, 3);
    }

    public function testProvideEmptyValue()
    {
        $elements = [
            [''],
            ['   '],
            [null],
            [0],
            [false],
            [[]],
        ];

        $generator = (new SimpleTestCase())->provideEmptyValue();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));
    }

    public function testProvideBooleanValue()
    {
        $elements = [
            [false],
            [true],
        ];

        $generator = (new SimpleTestCase())->provideBooleanValue();
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

        $generator = (new SimpleTestCase())->provideDateTimeInstance();
        $generatedElements = GeneratorUtility::getGeneratorElements($generator);

        /* @var DateTime $instance1 */
        $instance1 = $generatedElements[0][0];

        /* @var DateTime $instance2 */
        $instance2 = $generatedElements[1][0];

        /* @var DateTime $instance3 */
        $instance3 = $generatedElements[2][0];

        /* @var DateTime $instance4 */
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

        $generator = (new SimpleTestCase())->provideDateTimeRelativeFormat();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));
    }

    public function testProvideNotExistingFilePath()
    {
        $elements = [
            ['lets-test.doc'],
            ['lorem/ipsum.jpg'],
            ['surprise/me/one/more/time.txt'],
        ];

        $generator = (new SimpleTestCase())->provideNotExistingFilePath();
        self::assertEquals($elements, GeneratorUtility::getGeneratorElements($generator));
    }

    /**
     * @param string $fileName      Name of file
     * @param string $directoryPath Path of directory containing the file
     *
     * @dataProvider provideFileNameAndDirectoryPath
     */
    public function testGetFilePathForTesting($fileName, $directoryPath)
    {
        $path = (new SimpleTestCase())->getFilePathForTesting($fileName, $directoryPath);

        if (!empty($directoryPath)) {
            $directoryPath .= '/';
        }

        $expectedContains = sprintf('/data/tests/%s%s', $directoryPath, $fileName);
        static::assertContains($expectedContains, $path);
    }

    /**
     * Provides name of file and path of directory containing the file
     *
     * @return Generator
     */
    public function provideFileNameAndDirectoryPath()
    {
        yield[
            'abc.jpg',
            '',
        ];

        yield[
            'abc.def.jpg',
            '',
        ];

        yield[
            'abc.jpg',
            'def',
        ];

        yield[
            'abc.def.jpg',
            'def',
        ];
    }
}

/**
 * Simple test case
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class SimpleTestCase extends BaseTestCase
{
}
