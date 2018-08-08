<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Traits\CssSelector;

/**
 * Useful methods related to CSS selectors of form
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
trait FormCssSelector
{
    /**
     * Returns selector of form based on its name
     *
     * @param string $formName Name of form (value of the "name" attribute)
     * @return string
     */
    public static function getFormByNameSelector($formName)
    {
        $formName = trim($formName);

        if (empty($formName)) {
            return '';
        }

        return sprintf('form[name="%s"]', $formName);
    }

    /**
     * Returns selector of the input field based on its name
     *
     * @param string $formName  Name of form (value of the "name" attribute)
     * @param string $fieldName Name of field (value of the "name" attribute)
     * @return string
     */
    public static function getInputByNameSelector($formName, $fieldName)
    {
        $formSelector = static::getFormByNameSelector($formName);
        $fieldName = trim($fieldName);

        if (empty($formSelector) || empty($fieldName)) {
            return '';
        }

        return sprintf('%s input[name="%s"]', $formSelector, $fieldName);
    }

    /**
     * Returns selector of the input field based on its ID
     *
     * @param string $formName Name of form (value of the "name" attribute)
     * @param string $fieldId  ID of field (value of the "id" attribute)
     * @return string
     */
    public static function getInputByIdSelector($formName, $fieldId)
    {
        $formSelector = static::getFormByNameSelector($formName);
        $fieldId = trim($fieldId);

        if (empty($formSelector) || empty($fieldId)) {
            return '';
        }

        return sprintf('%s input#%s', $formSelector, $fieldId);
    }

    /**
     * Returns selector of label
     *
     * @param string $formName Name of form (value of the "name" attribute)
     * @param string $fieldId  ID of field (value of the "id" attribute)
     * @return string
     */
    public static function getLabelSelector($formName, $fieldId)
    {
        $formSelector = static::getFormByNameSelector($formName);
        $fieldId = trim($fieldId);

        if (empty($formSelector) || empty($fieldId)) {
            return '';
        }

        return sprintf('%s label[for="%s"]', $formSelector, $fieldId);
    }

    /**
     * Returns selector of field-set using index/position of the field-set
     *
     * @param string $formName      Name of form (value of the "name" attribute)
     * @param int    $fieldSetIndex Index/Position of the field-set
     * @return string
     */
    public static function getFieldSetByIndexSelector($formName, $fieldSetIndex)
    {
        $formSelector = static::getFormByNameSelector($formName);

        if (empty($formSelector) || 0 > $fieldSetIndex) {
            return '';
        }

        return sprintf('%s fieldset:nth-of-type(%d)', $formSelector, $fieldSetIndex);
    }
}
