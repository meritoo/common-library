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
    /**
     * The "major" part.
     * Incremented when you make incompatible API changes.
     *
     * @var int
     */
    private $majorPart;

    /**
     * The "minor" part.
     * Incremented when you add functionality in a backwards-compatible manner.
     *
     * @var int
     */
    private $minorPart;

    /**
     * The "patch" part.
     * Incremented when you make backwards-compatible bug fixes.
     *
     * @var int
     */
    private $patchPart;

    /**
     * Class constructor
     *
     * @param int $majorPart The "major" part. Incremented when you make incompatible API changes.
     * @param int $minorPart The "minor" part. Incremented when you add functionality in a backwards-compatible manner.
     * @param int $patchPart The "patch" part. Incremented when you make backwards-compatible bug fixes.
     */
    public function __construct($majorPart, $minorPart, $patchPart)
    {
        $this->majorPart = $majorPart;
        $this->minorPart = $minorPart;
        $this->patchPart = $patchPart;
    }

    /**
     * Returns the "major" part.
     * Incremented when you make incompatible API changes.
     *
     * @return int
     */
    public function getMajorPart()
    {
        return $this->majorPart;
    }

    /**
     * Returns the "minor" part.
     * Incremented when you add functionality in a backwards-compatible manner.
     *
     * @return int
     */
    public function getMinorPart()
    {
        return $this->minorPart;
    }

    /**
     * Returns the "patch" part.
     * Incremented when you make backwards-compatible bug fixes.
     *
     * @return int
     */
    public function getPatchPart()
    {
        return $this->patchPart;
    }

    /**
     * Returns representation of object as string
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%d.%d.%d', $this->getMajorPart(), $this->getMinorPart(), $this->getPatchPart());
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
     * @return Version|null
     */
    public static function fromString($version)
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

        $majorPart = (int)$matches[1];
        $minorPart = (int)$matches[2];
        $patchPart = (int)$matches[3];

        return new static($majorPart, $minorPart, $patchPart);
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
     * @return Version|null
     */
    public static function fromArray(array $version)
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

        $majorPart = (int)$version[0];
        $minorPart = (int)$version[1];
        $patchPart = (int)$version[2];

        return new static($majorPart, $minorPart, $patchPart);
    }
}
