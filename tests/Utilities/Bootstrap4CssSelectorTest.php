<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Bootstrap4CssSelector;

/**
 * Test case of the useful methods related to CSS selectors and the Bootstrap4 (front-end component library)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Bootstrap4CssSelectorTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(Bootstrap4CssSelector::class);
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetRadioButtonErrorSelectorUsingEmptyFormName($emptyValue)
    {
        $fieldSetIndex = 1;
        static::assertSame('', Bootstrap4CssSelector::getRadioButtonErrorSelector($emptyValue, $fieldSetIndex));
    }

    public function testGetRadioButtonErrorSelectorUsingNegativeFieldSetIndex()
    {
        static::assertSame('', Bootstrap4CssSelector::getRadioButtonErrorSelector('test-test', -1));
    }

    /**
     * @param string $formName      Name of form (value of the "name" attribute)
     * @param int    $fieldSetIndex Index/Position of the field-set
     * @param string $expected      Expected selector
     *
     * @dataProvider provideFormNameFieldSetIndexAndSelector
     */
    public function testGetRadioButtonErrorSelector($formName, $fieldSetIndex, $expected)
    {
        static::assertSame($expected, Bootstrap4CssSelector::getRadioButtonErrorSelector($formName, $fieldSetIndex));
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetFieldErrorSelectorUsingEmptyFormName($emptyValue)
    {
        $fieldName = 'test';
        static::assertSame('', Bootstrap4CssSelector::getFieldErrorSelector($emptyValue, $fieldName));
    }

    /**
     * @param string $emptyValue Name of field (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetFieldErrorSelectorUsingEmptyFieldName($emptyValue)
    {
        $formName = 'test';
        static::assertSame('', Bootstrap4CssSelector::getFieldErrorSelector($formName, $emptyValue));
    }

    /**
     * @param string $formName  Name of form (value of the "name" attribute)
     * @param string $fieldName Name of field (value of the "name" attribute)
     * @param string $expected  Expected selector
     *
     * @dataProvider provideFormNameFieldNameAndSelector
     */
    public function testGetFieldErrorSelector($formName, $fieldName, $expected)
    {
        static::assertSame($expected, Bootstrap4CssSelector::getFieldErrorSelector($formName, $fieldName));
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetFieldGroupSelectorUsingEmptyFormName($emptyValue)
    {
        static::assertSame('', Bootstrap4CssSelector::getFieldGroupSelector($emptyValue));
    }

    /**
     * @param string $formName Name of form (value of the "name" attribute)
     * @param string $expected Expected selector
     *
     * @dataProvider provideFormNameAndSelector
     */
    public function testGetFieldGroupSelector($formName, $expected)
    {
        static::assertSame($expected, Bootstrap4CssSelector::getFieldGroupSelector($formName));
    }

    public function testGetFieldErrorContainerSelector()
    {
        static::assertSame('.invalid-feedback .form-error-message', Bootstrap4CssSelector::getFieldErrorContainerSelector());
    }

    /**
     * Provides name of form, index/position of the field-set and expected selector
     *
     * @return \Generator
     */
    public function provideFormNameFieldSetIndexAndSelector()
    {
        yield[
            'test',
            0,
            'form[name="test"] fieldset:nth-of-type(0) legend.col-form-label .invalid-feedback .form-error-message',
        ];

        yield[
            'test-123-test-456',
            1,
            'form[name="test-123-test-456"] fieldset:nth-of-type(1) legend.col-form-label .invalid-feedback .form-error-message',
        ];

        yield[
            'test_something_098_different',
            1245,
            'form[name="test_something_098_different"] fieldset:nth-of-type(1245) legend.col-form-label .invalid-feedback .form-error-message',
        ];
    }

    /**
     * Provides name of form, name of field and expected selector
     *
     * @return \Generator
     */
    public function provideFormNameFieldNameAndSelector()
    {
        yield[
            'test',
            'test',
            'form[name="test"] label[for="test"] .invalid-feedback .form-error-message',
        ];

        yield[
            'test-123-test-456',
            'great-000-field',
            'form[name="test-123-test-456"] label[for="great-000-field"] .invalid-feedback .form-error-message',
        ];

        yield[
            'test_something_098_different',
            'this-is-the-123789-field',
            'form[name="test_something_098_different"] label[for="this-is-the-123789-field"] .invalid-feedback .form-error-message',
        ];
    }

    /**
     * Provides name of form and expected selector
     *
     * @return \Generator
     */
    public function provideFormNameAndSelector()
    {
        yield[
            'test',
            'form[name="test"] .form-group',
        ];

        yield[
            'test-123-test-456',
            'form[name="test-123-test-456"] .form-group',
        ];

        yield[
            'test_something_098_different',
            'form[name="test_something_098_different"] .form-group',
        ];
    }
}
