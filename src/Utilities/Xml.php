<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Utilities;

use DOMDocument;
use DOMXPath;
use SimpleXMLElement;

/**
 * Useful XML-related methods (only static functions)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Xml
{
    /**
     * Merges nodes of given elements.
     * Returns merged instance of SimpleXMLElement.
     *
     * @param SimpleXMLElement $element1 First element to merge
     * @param SimpleXMLElement $element2 Second element to merge
     * @return SimpleXMLElement
     */
    public static function mergeNodes(SimpleXMLElement $element1, SimpleXMLElement $element2)
    {
        $document1 = new DOMDocument();
        $document2 = new DOMDocument();

        $document1->loadXML($element1->asXML());
        $document2->loadXML($element2->asXML());

        $path = new DOMXPath($document2);
        $query = $path->query('/*/*');
        $nodesCount = $query->length;

        if (0 == $nodesCount) {
            return $element1;
        }

        for ($i = 0; $i < $nodesCount; ++$i) {
            $node = $document1->importNode($query->item($i), true);
            $document1->documentElement->appendChild($node);
        }

        return simplexml_import_dom($document1);
    }
}
