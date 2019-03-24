<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\ValueObject;

use Meritoo\Common\Exception\ValueObject\InvalidSizeDimensionsException;
use Meritoo\Common\Utilities\Regex;

/**
 * Size, e.g. of image
 *
 * Instance of this class may be created using static methods:
 * - fromString()
 * - fromArray()
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Size
{
    /**
     * The width
     *
     * @var int
     */
    protected $width;

    /**
     * The height
     *
     * @var int
     */
    protected $height;

    /**
     * Unit used when width or height should be returned with unit
     *
     * @var string
     */
    protected $unit;

    /**
     * Separator used when converting to string
     *
     * @var string
     */
    protected $separator = ' x ';

    /**
     * Class constructor
     *
     * @param int    $width  (optional) The width
     * @param int    $height (optional) The height
     * @param string $unit   (optional) Unit used when width or height should be returned with unit. Default: "px".
     *
     * @throws InvalidSizeDimensionsException
     */
    private function __construct($width = null, $height = null, $unit = 'px')
    {
        $width = (int)$width;
        $height = (int)$height;

        if ($width < 0 || $height < 0) {
            throw new InvalidSizeDimensionsException($width, $height);
        }

        $this
            ->setWidth($width)
            ->setHeight($height)
        ;

        $this->unit = $unit;
    }

    /**
     * Returns string representation of instance of this class in human readable format, e.g. '200 x 100'
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Sets separator used when converting to string
     *
     * @param string $separator The separator
     * @return Size
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Returns the width
     *
     * @param bool $withUnit (optional) If is set to true, width is returned with unit ("px"). Otherwise - without
     *                       (default behaviour).
     * @return int|string
     */
    public function getWidth($withUnit = false)
    {
        if ($withUnit) {
            return sprintf('%d %s', $this->width, $this->unit);
        }

        return $this->width;
    }

    /**
     * Sets the width
     *
     * @param int|string $width The width
     * @return Size
     */
    public function setWidth($width)
    {
        $this->width = (int)$width;

        return $this;
    }

    /**
     * Returns the height
     *
     * @param bool $withUnit (optional) If is set to true, height is returned with unit ("px"). Otherwise - without
     *                       (default behaviour).
     * @return int|string
     */
    public function getHeight($withUnit = false)
    {
        if ($withUnit) {
            return sprintf('%d %s', $this->height, $this->unit);
        }

        return $this->height;
    }

    /**
     * Sets the height
     *
     * @param int $height The height
     * @return Size
     */
    public function setHeight($height)
    {
        $this->height = (int)$height;

        return $this;
    }

    /**
     * Returns string representation of instance of this class, e.g. '200 x 100' or '200x100'
     *
     * @param bool $withUnit (optional) If is set to true, width and height are returned with unit ("px"). Otherwise
     *                       - without (default behaviour).
     * @return string
     */
    public function toString($withUnit = false)
    {
        $width = $this->getWidth($withUnit);
        $height = $this->getHeight($withUnit);

        return sprintf('%s%s%s', $width, $this->separator, $height);
    }

    /**
     * Returns instance of this class as an array.
     * Values of the array are width and height, eg. [800, 600] or ['800px', '600px'].
     *
     * @param bool $withUnits (optional) If is set to true, width and height are returned with unit ("px"). Otherwise
     *                        - without (default behaviour).
     * @return array
     */
    public function toArray($withUnits = false)
    {
        return [
            $this->getWidth($withUnits),
            $this->getHeight($withUnits),
        ];
    }

    /**
     * Creates new instance from given string
     *
     * @param string $size      The size represented as string (width and height separated by given separator)
     * @param string $unit      (optional) Unit used when width or height should be returned with unit. Default: "px".
     * @param string $separator (optional) Separator used to split width and height. Default: " x ".
     * @return Size|null
     */
    public static function fromString($size, $unit = 'px', $separator = ' x ')
    {
        if (is_string($size)) {
            $matches = [];
            $pattern = Regex::getSizePattern($separator);

            if ((bool)preg_match($pattern, $size, $matches)) {
                $width = (int)$matches[1];
                $height = (int)$matches[2];
                $sizeObject = new self($width, $height, $unit);

                return $sizeObject->setSeparator($separator);
            }
        }

        return null;
    }

    /**
     * Creates new instance from given array
     *
     * The array should contain 2 elements: width and height.
     * Examples: ['800', '600'], [800, 600].
     *
     * @param array  $array The size represented as array
     * @param string $unit  (optional) Unit used when width or height should be returned with unit. Default: "px".
     * @return Size|null
     */
    public static function fromArray(array $array, $unit = 'px')
    {
        // Requirements for given array:
        // - indexes "0" and "1"
        // - should contains exactly 2 elements
        if (
            array_key_exists(0, $array)
            && array_key_exists(1, $array)
            && 2 === count($array)
        ) {
            list($width, $height) = $array;

            return new self($width, $height, $unit);
        }

        return null;
    }
}
