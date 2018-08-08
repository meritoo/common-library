<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\CssSelector;

/**
 * Test case of the useful methods related to CSS selectors
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class CssSelectorTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(CssSelector::class);
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetFormByNameSelectorUsingEmptyName($emptyValue)
    {
        static::assertSame('', CssSelector::getFormByNameSelector($emptyValue));
    }

    /**
     * @param string $formName Name of form (value of the "name" attribute)
     * @param string $expected Expected selector
     *
     * @dataProvider provideFormNameAndSelector
     */
    public function testGetFormByNameSelector($formName, $expected)
    {
        static::assertSame($expected, CssSelector::getFormByNameSelector($formName));
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetInputByNameSelectorUsingEmptyFormName($emptyValue)
    {
        $fieldName = 'test-test';
        static::assertSame('', CssSelector::getInputByNameSelector($emptyValue, $fieldName));
    }

    /**
     * @param string $emptyValue Name of field (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetInputByNameSelectorUsingEmptyFieldName($emptyValue)
    {
        $formName = 'test-test';
        static::assertSame('', CssSelector::getInputByNameSelector($formName, $emptyValue));
    }

    /**
     * @param string $formName  Name of form (value of the "name" attribute)
     * @param string $fieldName Name of field (value of the "name" attribute)
     * @param string $expected  Expected selector
     *
     * @dataProvider provideFormNameFieldNameAndSelector
     */
    public function testGetInputByNameSelector($formName, $fieldName, $expected)
    {
        static::assertSame($expected, CssSelector::getInputByNameSelector($formName, $fieldName));
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetInputByIdSelectorUsingEmptyFormName($emptyValue)
    {
        $fieldId = 'test-test';
        static::assertSame('', CssSelector::getInputByIdSelector($emptyValue, $fieldId));
    }

    /**
     * @param string $emptyValue ID of field (value of the "id" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetInputByIdSelectorUsingEmptyFieldName($emptyValue)
    {
        $formName = 'test-test';
        static::assertSame('', CssSelector::getInputByIdSelector($formName, $emptyValue));
    }

    /**
     * @param string $formName Name of form (value of the "name" attribute)
     * @param string $fieldId  ID of field (value of the "id" attribute)
     * @param string $expected Expected selector
     *
     * @dataProvider provideFormNameFieldIdAndSelector
     */
    public function testGetInputByIdSelector($formName, $fieldId, $expected)
    {
        static::assertSame($expected, CssSelector::getInputByIdSelector($formName, $fieldId));
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetLabelSelectorUsingEmptyFormName($emptyValue)
    {
        $fieldId = 'test-test';
        static::assertSame('', CssSelector::getLabelSelector($emptyValue, $fieldId));
    }

    /**
     * @param string $emptyValue ID of field (value of the "id" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetLabelSelectorUsingEmptyFieldId($emptyValue)
    {
        $formName = 'test-test';
        static::assertSame('', CssSelector::getLabelSelector($formName, $emptyValue));
    }

    /**
     * @param string $formName Name of form (value of the "name" attribute)
     * @param string $fieldId  ID of field (value of the "id" attribute)
     * @param string $expected Expected selector
     *
     * @dataProvider provideFormNameFieldIdAndLabelSelector
     */
    public function testGetLabelSelector($formName, $fieldId, $expected)
    {
        static::assertSame($expected, CssSelector::getLabelSelector($formName, $fieldId));
    }

    /**
     * @param string $emptyValue Name of form (value of the "name" attribute)
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetFieldSetByIndexSelectorUsingEmptyFormName($emptyValue)
    {
        $fieldSetIndex = 1;
        static::assertSame('', CssSelector::getFieldSetByIndexSelector($emptyValue, $fieldSetIndex));
    }

    public function testGetFieldSetByIndexSelectorUsingNegativeFieldSetIndex()
    {
        static::assertSame('', CssSelector::getFieldSetByIndexSelector('test-test', -1));
    }

    /**
     * @param string $formName      Name of form (value of the "name" attribute)
     * @param int    $fieldSetIndex Index/Position of the field-set
     * @param string $expected      Expected selector
     *
     * @dataProvider provideFormNameFieldSetIndexAndSelector
     */
    public function testGetFieldSetByIndexSelector($formName, $fieldSetIndex, $expected)
    {
        static::assertSame($expected, CssSelector::getFieldSetByIndexSelector($formName, $fieldSetIndex));
    }

    /**
     * Provides name of form and selector of the form
     *
     * @return \Generator
     */
    public function provideFormNameAndSelector()
    {
        yield[
            'test',
            'form[name="test"]',
        ];

        yield[
            'test-123-test-456',
            'form[name="test-123-test-456"]',
        ];

        yield[
            'test_something_098_different',
            'form[name="test_something_098_different"]',
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
            'form[name="test"] input[name="test"]',
        ];

        yield[
            'test-123-test-456',
            'great-000-field',
            'form[name="test-123-test-456"] input[name="great-000-field"]',
        ];

        yield[
            'test_something_098_different',
            'this-is-the-123789-field',
            'form[name="test_something_098_different"] input[name="this-is-the-123789-field"]',
        ];
    }

    /**
     * Provides name of form, ID of field and expected selector of label
     *
     * @return \Generator
     */
    public function provideFormNameFieldIdAndLabelSelector()
    {
        yield[
            'test',
            'test',
            'form[name="test"] label[for="test"]',
        ];

        yield[
            'test-123-test-456',
            'great-000-field',
            'form[name="test-123-test-456"] label[for="great-000-field"]',
        ];

        yield[
            'test_something_098_different',
            'this-is-the-123789-field',
            'form[name="test_something_098_different"] label[for="this-is-the-123789-field"]',
        ];
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
            'form[name="test"] fieldset:nth-of-type(0)',
        ];

        yield[
            'test-123-test-456',
            1,
            'form[name="test-123-test-456"] fieldset:nth-of-type(1)',
        ];

        yield[
            'test_something_098_different',
            1245,
            'form[name="test_something_098_different"] fieldset:nth-of-type(1245)',
        ];
    }

    /**
     * Provides name of form, ID of field and expected selector
     *
     * @return \Generator
     */
    public function provideFormNameFieldIdAndSelector()
    {
        yield[
            'test',
            'test',
            'form[name="test"] input#test',
        ];

        yield[
            'test-123-test-456',
            'great-000-field',
            'form[name="test-123-test-456"] input#great-000-field',
        ];

        yield[
            'test_something_098_different',
            'this-is-the-123789-field',
            'form[name="test_something_098_different"] input#this-is-the-123789-field',
        ];
    }
}
