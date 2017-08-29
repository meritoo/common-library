<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

/**
 * Useful methods for bundle
 *
 * @author     Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright  Meritoo.pl
 */
class Bundle
{
    /**
     * Returns path to view / template of given bundle
     *
     * @param string $viewPath   Path of the view / template, e.g. "MyDirectory/my-template"
     * @param string $bundleName Name of the bundle, e.g. "MyExtraBundle"
     * @param string $extension  (optional) Extension of the view / template
     * @return string|null
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

        /*
         * Path of the view / template doesn't end with given extension?
         */
        if (!Regex::endsWith($viewPath, $extension)) {
            $viewPath = sprintf('%s.%s', $viewPath, $extension);
        }

        return sprintf('%s:%s', $bundleName, $viewPath);
    }
}
