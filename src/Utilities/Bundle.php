<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use Meritoo\Common\Exception\Bundle\IncorrectBundleNameException;

/**
 * Useful methods for bundle
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Bundle
{
    /**
     * Returns path of given bundle to view / template with given extension
     *
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     * @param string $extension  (optional) Extension of the view / template (default: "html.twig")
     * @throws IncorrectBundleNameException
     * @return null|string
     */
    public static function getBundleViewPath($viewPath, $bundleName, $extension = 'html.twig')
    {
        /*
         * Unknown path, extension of the view / template or name of the bundle?
         * Nothing to do
         */
        if (empty($viewPath) || empty($bundleName) || empty($extension)) {
            return null;
        }

        // Oops, given name of bundle is invalid
        if (!Regex::isValidBundleName($bundleName)) {
            throw IncorrectBundleNameException::create($bundleName);
        }

        // Make sure that path of the view / template ends with given extension
        if (!Regex::endsWith($viewPath, $extension)) {
            $viewPath = sprintf('%s.%s', $viewPath, $extension);
        }

        // Prepare short name of bundle and path of view / template with "/" (instead of ":")
        $shortBundleName = static::getShortBundleName($bundleName);
        $viewPath = str_replace(':', '/', $viewPath);

        return sprintf('@%s/%s', $shortBundleName, $viewPath);
    }

    /**
     * Returns short name of bundle (without "Bundle")
     *
     * @param string $fullBundleName Full name of the bundle, e.g. "MyExtraBundle"
     * @throws IncorrectBundleNameException
     * @return null|string
     */
    public static function getShortBundleName(string $fullBundleName): ?string
    {
        // Oops, given name of bundle is invalid
        if (!Regex::isValidBundleName($fullBundleName)) {
            throw new IncorrectBundleNameException($fullBundleName);
        }

        $matches = [];
        $pattern = Regex::getBundleNamePattern();
        preg_match($pattern, $fullBundleName, $matches);

        return $matches[1];
    }
}
