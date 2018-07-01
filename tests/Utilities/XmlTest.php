<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Xml;
use SimpleXMLElement;

/**
 * Test case of the useful XML-related methods (only static functions)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class XmlTest extends BaseTestCase
{
    private $simpleXml;
    private $advancedXml;

    public function testConstructor()
    {
        static::assertHasNoConstructor(Xml::class);
    }

    public function testMergeNodes()
    {
        /*
         * An empty XMLs
         */
        $element1 = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><cars />');
        $element2 = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><employees />');

        $merged = Xml::mergeNodes($element1, $element2);
        self::assertEquals('', (string)$merged);

        /*
         * XMLs with data
         */
        $element1 = new SimpleXMLElement($this->simpleXml);
        $element2 = new SimpleXMLElement($this->advancedXml);

        $merged = Xml::mergeNodes($element1, $element2);
        self::assertEquals('John', (string)$merged->author[0]->first_name);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->simpleXml = '<?xml version="1.0" encoding="UTF-8"?>
            <notes>
                <note>Lorem ipsum</note>
                <note>Dolor sit amet</note>
                <note>Consectetur adipiscing elit</note>
                <note>Donec ut</note>
                <note>Mi a magna</note>
                <note>Dapibus bibendum</note>
            </notes>
        ';

        $this->advancedXml = '<?xml version="1.0" encoding="UTF-8"?>
            <authors>
                <author>
                    <first_name>John</first_name>
                    <last_name>Scott</last_name>
                    <email>john.scott@fake.email</email>
                </author>
                <author>
                    <first_name>Julia</first_name>
                    <last_name>Brown</last_name>
                    <email>julia.brown@fake.email</email>
                </author>
            </authors>
        ';
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->simpleXml);
        unset($this->advancedXml);
    }
}
