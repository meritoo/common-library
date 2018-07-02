<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use stdClass;

/**
 * Useful Composer-related methods (only static functions)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Composer
{
    /**
     * Name of the Composer's main file with configuration in Json format
     *
     * @var string
     */
    const FILE_NAME_MAIN = 'composer.json';

    /**
     * Returns value from composer.json file
     *
     * @param string $composerJsonPath Path of composer.json file
     * @param string $nodeName         Name of node who value should be returned
     * @return string|null
     */
    public static function getValue($composerJsonPath, $nodeName)
    {
        $composerJsonString = is_string($composerJsonPath);
        $composerJsonReadable = false;

        if ($composerJsonString) {
            $composerJsonReadable = is_readable($composerJsonPath);
        }

        /*
         * Provided path or name of node are invalid?
         * The composer.json file doesn't exist or isn't readable?
         * Name of node is unknown?
         *
         * Nothing to do
         */
        if (!$composerJsonString || !is_string($nodeName) || !$composerJsonReadable || empty($nodeName)) {
            return null;
        }

        $content = file_get_contents($composerJsonPath);
        $data = json_decode($content);

        /*
         * Unknown data from the composer.json file or there is no node with given name?
         * Nothing to do
         */
        if (null === $data || !isset($data->{$nodeName})) {
            return null;
        }

        /* @var stdClass $data */
        return $data->{$nodeName};
    }
}
