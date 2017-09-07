<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use DateTime;
use Generator;

/**
 * Test case with common methods and data providers
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides an empty value
     *
     * @return Generator
     */
    public function provideEmptyValue()
    {
        yield[''];
        yield['   '];
        yield[null];
        yield[0];
        yield[false];
        yield[[]];
    }

    /**
     * Provides boolean value
     *
     * @return Generator
     */
    public function provideBooleanValue()
    {
        yield[false];
        yield[true];
    }

    /**
     * Provides instance of DateTime class
     *
     * @return Generator
     */
    public function provideDateTimeInstance()
    {
        yield[new DateTime()];
        yield[new DateTime('yesterday')];
        yield[new DateTime('now')];
        yield[new DateTime('tomorrow')];
    }

    /**
     * Provides relative / compound format of DateTime
     *
     * @return Generator
     */
    public function provideDateTimeRelativeFormat()
    {
        yield['now'];
        yield['yesterday'];
        yield['tomorrow'];
        yield['back of 10'];
        yield['front of 10'];
        yield['last day of February'];
        yield['first day of next month'];
        yield['last day of previous month'];
        yield['last day of next month'];
        yield['Y-m-d'];
        yield['Y-m-d 10:00'];
    }

    /**
     * Provides path of not existing file, e.g. "lorem/ipsum.jpg"
     *
     * @return Generator
     */
    public function provideNotExistingFilePath()
    {
        yield['lets-test.doc'];
        yield['lorem/ipsum.jpg'];
        yield['suprise/me/one/more/time.txt'];
    }

    /**
     * Returns path of file used by tests.
     * It should be placed in /data/tests directory of this project.
     *
     * @param string $fileName      Name of file
     * @param string $directoryPath (optional) Path of directory containing the file
     * @return string
     */
    public function getFilePathToTests($fileName, $directoryPath = '')
    {
        if (!empty($directoryPath)) {
            $directoryPath = '/' . $directoryPath;
        }

        return sprintf('%s/../../../../data/tests/%s%s', __DIR__, $fileName, $directoryPath);
    }
}
