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
    protected int $width;
    protected int $height;
    protected string $unit;
    protected string $separator = ' x ';

    private function __construct(int $width = 0, int $height = 0, string $unit = 'px')
    {
        if ($width < 0 || $height < 0) {
            throw new InvalidSizeDimensionsException($width, $height);
        }

        $this
            ->setWidth($width)
            ->setHeight($height)
        ;

        $this->unit = $unit;
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Creates new instance from given array
     *
     * The array should contain 2 elements: width and height.
     * Examples: ['800', '600'], [800, 600].
     *
     * @param array  $array The size represented as array
     * @param string $unit  (optional) Unit used when width or height should be returned with unit. Default: "px".
     *
     * @return null|Size
     * @throws InvalidSizeDimensionsException
     */
    public static function fromArray(array $array, string $unit = 'px'): ?Size
    {
        // Requirements for given array:
        // - indexes "0" and "1"
        // - should contain exactly 2 elements
        if (
            array_key_exists(0, $array)
            && array_key_exists(1, $array)
            && 2 === count($array)
        ) {
            [$width, $height] = $array;

            return new self((int) $width, (int) $height, $unit);
        }

        return null;
    }

    /**
     * Creates new instance from given string
     *
     * @param string $size      The size represented as string (width and height separated by given separator)
     * @param string $unit      (optional) Unit used when width or height should be returned with unit. Default: "px".
     * @param string $separator (optional) Separator used to split width and height. Default: " x ".
     *
     * @return null|Size
     * @throws InvalidSizeDimensionsException
     */
    public static function fromString(string $size, string $unit = 'px', string $separator = ' x '): ?Size
    {
        $matches = [];
        $pattern = Regex::getSizePattern($separator);

        if (preg_match($pattern, $size, $matches)) {
            $width = (int) $matches[1];
            $height = (int) $matches[2];

            return (new self($width, $height, $unit))->setSeparator($separator);
        }

        return null;
    }

    public function getHeight(bool $withUnit = false)
    {
        if ($withUnit) {
            return sprintf('%d %s', $this->height, $this->unit);
        }

        return $this->height;
    }

    public function setHeight(int $height): Size
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(bool $withUnit = false)
    {
        if ($withUnit) {
            return sprintf('%d %s', $this->width, $this->unit);
        }

        return $this->width;
    }

    public function setWidth(int $width): Size
    {
        $this->width = $width;

        return $this;
    }

    public function setSeparator(string $separator): Size
    {
        $this->separator = $separator;

        return $this;
    }

    public function toArray(bool $withUnits = false): array
    {
        return [
            $this->getWidth($withUnits),
            $this->getHeight($withUnits),
        ];
    }

    public function toString(bool $withUnit = false): string
    {
        $width = $this->getWidth($withUnit);
        $height = $this->getHeight($withUnit);

        return sprintf('%s%s%s', $width, $this->separator, $height);
    }
}
