<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\ValueObject;

/**
 * Version of software
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Version
{
    protected int $majorPart;
    protected int $minorPart;
    protected int $patchPart;

    public function __construct(int $majorPart, int $minorPart, int $patchPart)
    {
        $this->majorPart = $majorPart;
        $this->minorPart = $minorPart;
        $this->patchPart = $patchPart;
    }

    public function __toString(): string
    {
        return sprintf('%d.%d.%d', $this->getMajorPart(), $this->getMinorPart(), $this->getPatchPart());
    }

    /**
     * Returns new instance based on given version as array.
     * Given version should contain 3 integers, 1 per each part ("major", "minor" and "patch").
     *
     * Examples:
     * [1, 0, 2];
     * [10, 4, 0];
     *
     * @param array $version The version
     * @return null|Version
     */
    public static function fromArray(array $version): ?Version
    {
        /*
         * No version provided?
         * Nothing to do
         */
        if (empty($version)) {
            return null;
        }

        $count = count($version);

        /*
         * Incorrect version?
         * Nothing to do
         */
        if (3 !== $count) {
            return null;
        }

        $majorPart = (int) $version[0];
        $minorPart = (int) $version[1];
        $patchPart = (int) $version[2];

        return new static($majorPart, $minorPart, $patchPart);
    }

    /**
     * Returns new instance based on given version as string.
     * Given version should contain 3 dot-separated integers, 1 per each part ("major", "minor" and "patch").
     *
     * Examples:
     * "1.0.2";
     * "10.4.0";
     *
     * @param string $version The version
     * @return null|Version
     */
    public static function fromString(string $version): ?Version
    {
        $version = trim($version);

        /*
         * No version provided?
         * Nothing to do
         */
        if (empty($version)) {
            return null;
        }

        $matches = [];
        $pattern = '/^(\d+)\.(\d+)\.(\d+)$/'; // e.g. "1.0.2"
        $matched = preg_match($pattern, $version, $matches);

        /*
         * Incorrect version?
         * Nothing to do
         */
        if (0 === $matched || false === $matched) {
            return null;
        }

        $majorPart = (int) $matches[1];
        $minorPart = (int) $matches[2];
        $patchPart = (int) $matches[3];

        return new static($majorPart, $minorPart, $patchPart);
    }

    public function getMajorPart(): int
    {
        return $this->majorPart;
    }

    public function getMinorPart(): int
    {
        return $this->minorPart;
    }

    public function getPatchPart(): int
    {
        return $this->patchPart;
    }
}
