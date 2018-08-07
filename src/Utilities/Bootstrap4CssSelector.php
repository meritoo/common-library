<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

/**
 * Useful methods related to CSS selectors and the Bootstrap4 (front-end component library)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Bootstrap4CssSelector
{
    /**
     * Returns selector of container with field's validation error
     *
     * @return string
     */
    public static function getFieldErrorContainerSelector()
    {
        return '.invalid-feedback .form-error-message';
    }

    /**
     * Returns selector of field's validation error
     *
     * @param string $formName Name of form (value of the "name" attribute)
     * @param string $fieldId  ID of field (value of the "id" attribute)
     * @return string
     */
    public static function getFieldErrorSelector($formName, $fieldId)
    {
        $labelSelector = CssSelector::getLabelSelector($formName, $fieldId);

        if (empty($labelSelector)) {
            return '';
        }

        $errorContainerSelector = static::getFieldErrorContainerSelector();

        return sprintf('%s %s', $labelSelector, $errorContainerSelector);
    }

    /**
     * Returns selector of radio-button's validation error
     *
     * @param string $formName      Name of form (value of the "name" attribute)
     * @param int    $fieldSetIndex Index/Position of the field-set
     * @return string
     */
    public static function getRadioButtonErrorSelector($formName, $fieldSetIndex)
    {
        $fieldSetSelector = CssSelector::getFieldSetByIndexSelector($formName, $fieldSetIndex);

        if (empty($fieldSetSelector)) {
            return '';
        }

        $errorContainerSelector = static::getFieldErrorContainerSelector();

        return sprintf('%s legend.col-form-label %s', $fieldSetSelector, $errorContainerSelector);
    }

    /**
     * Returns selector of field's group
     *
     * @param string $formName Name of form (value of the "name" attribute)
     * @return string
     */
    public static function getFieldGroupSelector($formName)
    {
        $formSelector = CssSelector::getFormByNameSelector($formName);

        if (empty($formSelector)) {
            return '';
        }

        return sprintf('%s .form-group', $formSelector);
    }
}
