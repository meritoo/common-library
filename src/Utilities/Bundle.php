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
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template". Extension is not required.
     * @param string $bundleName Full name of the bundle, e.g. "MyExtraBundle"
     * @param string $extension  (optional) Extension of the view / template (default: "html.twig")
     * @throws IncorrectBundleNameException
     * @return null|string
     */
    public static function getBundleViewPath(
        string $viewPath,
        string $bundleName,
        string $extension = 'html.twig'
    ): ?string {
        // Nothing to do, because at least one unknown argument provided
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
        $shortName = static::getShortBundleName($bundleName);
        $path = str_replace(':', '/', $viewPath);

        return sprintf('@%s/%s', $shortName, $path);
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
