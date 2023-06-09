<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Traits\Test\Base\BaseTestCaseTrait;
use Meritoo\Common\Utilities\Arrays;
use Meritoo\Common\Utilities\MimeTypes;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(MimeTypes::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(Arrays::class)]
class MimeTypesTest extends BaseTestCase
{
    /**
     * Provides real file path to get information if the file is an image
     *
     * @return Generator
     */
    public static function provideExistingFilePathToCheckIsImagePath(): Generator
    {
        yield [
            self::getFilePathForTesting('minion.jpg'),
            true,
        ];

        yield [
            self::getFilePathForTesting('lorem-ipsum.txt'),
            false,
        ];
    }

    /**
     * Provides real file path to get mime type
     *
     * @return Generator
     */
    public static function provideFilePathToGetMimeTypeOfRealFile(): Generator
    {
        yield [
            self::getFilePathForTesting('minion.jpg'),
            'image/jpeg',
        ];

        yield [
            self::getFilePathForTesting('lorem-ipsum.txt'),
            'text/plain',
        ];
    }

    /**
     * Provides mime type of image
     *
     * @return Generator
     */
    public static function provideImageMimeType(): Generator
    {
        yield ['image/bmp'];
        yield ['image/jpeg'];
        yield ['image/png'];
        yield ['image/tiff'];
        yield ['image/vnd.microsoft.icon'];
        yield ['image/x-rgb'];
    }

    /**
     * Provides existing mime type used to get multiple, more than one extension
     *
     * @return Generator
     */
    public static function provideMimeTypeToGetMultipleExtension(): Generator
    {
        yield [
            'application/postscript',
            [
                'ai',
                'eps',
                'ps',
            ],
        ];

        yield [
            'audio/midi',
            [
                'mid',
                'midi',
                'kar',
                'rmi',
            ],
        ];

        yield [
            'image/jpeg',
            [
                'jpeg',
                'jpe',
                'jpg',
            ],
        ];

        yield [
            'text/html',
            [
                'html',
                'htm',
            ],
        ];

        yield [
            'text/plain',
            [
                'txt',
                'text',
                'conf',
                'def',
                'list',
                'log',
                'in',
            ],
        ];

        yield [
            'video/mp4',
            [
                'mp4',
                'mp4v',
                'mpg4',
                'm4v',
            ],
        ];
    }

    /**
     * Provides existing mime type used to get single, one extension
     *
     * @return Generator
     */
    public static function provideMimeTypeToGetSingleExtension(): Generator
    {
        yield [
            'application/x-7z-compressed',
            '7z',
        ];

        yield [
            'application/json',
            'json',
        ];

        yield [
            'application/zip',
            'zip',
        ];
    }

    /**
     * Provides mime types used to get extensions
     *
     * @return Generator
     */
    public static function provideMimesTypesToGetExtensions(): Generator
    {
        yield [
            [
                'application/x-7z-compressed',
                'application/json',
            ],
            [
                'application/x-7z-compressed' => '7z',
                'application/json' => 'json',
            ],
        ];

        yield [
            [
                'application/mathematica',
                'application/xml',
                'audio/mp4',
                'video/mp4',
            ],
            [
                'application/mathematica' => [
                    'ma',
                    'nb',
                    'mb',
                ],
                'application/xml' => [
                    'xml',
                    'xsl',
                ],
                'audio/mp4' => 'mp4a',
                'video/mp4' => [
                    'mp4',
                    'mp4v',
                    'mpg4',
                    'm4v',
                ],
            ],
        ];
    }

    /**
     * Provides mime types used to get extensions as upper case
     *
     * @return Generator
     */
    public static function provideMimesTypesToGetExtensionsUpperCase(): Generator
    {
        yield [
            [
                'application/x-7z-compressed',
                'application/json',
            ],
            [
                'application/x-7z-compressed' => '7Z',
                'application/json' => 'JSON',
            ],
        ];

        yield [
            [
                'application/xml',
                'audio/mp4',
                'text/html',
                'video/mp4',
            ],
            [
                'application/xml' => [
                    'XML',
                    'XSL',
                ],
                'audio/mp4' => 'MP4A',
                'text/html' => [
                    'HTML',
                    'HTM',
                ],
                'video/mp4' => [
                    'MP4',
                    'MP4V',
                    'MPG4',
                    'M4V',
                ],
            ],
        ];
    }

    /**
     * Provides mime type of non-image
     *
     * @return Generator
     */
    public static function provideNonImageMimeType(): Generator
    {
        yield ['application/rtf'];
        yield ['audio/mp4'];
        yield ['text/plain'];
        yield ['text/html'];
    }

    /**
     * Provides not existing mime type
     *
     * @return Generator
     */
    public static function provideNotExistingMimeType(): Generator
    {
        yield ['lorem/ipsum'];
        yield ['dolor'];
        yield ['x/y/z'];
    }

    /**
     * Provides not existing mime types
     *
     * @return Generator
     */
    public static function provideNotExistingMimeTypes(): Generator
    {
        yield [
            [],
        ];

        yield [
            [
                '',
                '0',
                'xyz',
                0,
                123,
                false,
            ],
        ];

        yield [
            [
                'lorem/ipsum',
                'dolor/sit',
            ],
        ];
    }

    public function testConstructor()
    {
        static::assertHasNoConstructor(MimeTypes::class);
    }

    /**
     * @param bool $mimeType The mime type, e.g. "video/mpeg"
     * @dataProvider provideBooleanValue
     */
    public function testGetExtensionBooleanMimeType($mimeType)
    {
        self::assertEquals('', MimeTypes::getExtension($mimeType));
    }

    /**
     * @param mixed $mimeType Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetExtensionEmptyMimeType($mimeType)
    {
        self::assertEquals('', MimeTypes::getExtension($mimeType));
    }

    /**
     * @param string $mimeType   The mime type, e.g. "video/mpeg"
     * @param array  $extensions Expected extensions
     *
     * @dataProvider provideMimeTypeToGetMultipleExtension
     */
    public function testGetExtensionMultiple($mimeType, $extensions)
    {
        self::assertEquals($extensions, MimeTypes::getExtension($mimeType));
    }

    /**
     * @param string $mimeType Not existing mime type, e.g. "lorem/ipsum"
     * @dataProvider provideNotExistingMimeType
     */
    public function testGetExtensionNotExistingMimeType($mimeType)
    {
        self::assertEquals('', MimeTypes::getExtension($mimeType));
    }

    /**
     * @param string $mimeType  The mime type, e.g. "video/mpeg"
     * @param string $extension Expected extension
     *
     * @dataProvider provideMimeTypeToGetSingleExtension
     */
    public function testGetExtensionSingle($mimeType, $extension)
    {
        self::assertEquals($extension, MimeTypes::getExtension($mimeType));
    }

    /**
     * @param array $mimesTypes The mimes types, e.g. ['video/mpeg', 'image/jpeg']
     * @param array $extensions Expected extensions
     *
     * @dataProvider provideMimesTypesToGetExtensions
     */
    public function testGetExtensions($mimesTypes, $extensions)
    {
        self::assertEquals($extensions, MimeTypes::getExtensions($mimesTypes));
    }

    /**
     * @param array $mimesTypes Not existing mimes types, e.g. ['lorem/ipsum']
     * @dataProvider provideNotExistingMimeTypes
     */
    public function testGetExtensionsNotExistingMimeTypes($mimesTypes)
    {
        self::assertEquals([], MimeTypes::getExtensions($mimesTypes));
    }

    /**
     * @param array $mimesTypes The mimes types, e.g. ['video/mpeg', 'image/jpeg']
     * @param array $extensions Expected extensions
     *
     * @dataProvider provideMimesTypesToGetExtensionsUpperCase
     */
    public function testGetExtensionsUpperCase($mimesTypes, $extensions)
    {
        self::assertEquals($extensions, MimeTypes::getExtensions($mimesTypes, true));
    }

    /**
     * @param mixed $filePath Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testGetMimeTypeEmptyPath($filePath)
    {
        self::assertEquals('', MimeTypes::getMimeType($filePath));
    }

    /**
     * @param string $filePath Path of the file to check
     * @param string $mimeType Expected mime type
     *
     * @dataProvider provideFilePathToGetMimeTypeOfRealFile
     */
    public function testGetMimeTypeOfRealFile($filePath, $mimeType)
    {
        self::assertEquals($mimeType, MimeTypes::getMimeType($filePath));
    }

    /**
     * @param mixed $mimeType Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testIsImageEmptyMimeType($mimeType)
    {
        self::assertFalse(MimeTypes::isImage($mimeType));
    }

    /**
     * @param string $mimeType Mime type of image, e.g. "image/jpeg"
     * @dataProvider provideImageMimeType
     */
    public function testIsImageImageMimeType($mimeType)
    {
        self::assertTrue(MimeTypes::isImage($mimeType));
    }

    /**
     * @param string $mimeType Mime type of non-image, e.g. "text/plain"
     * @dataProvider provideNonImageMimeType
     */
    public function testIsImageNonImageMimeType($mimeType)
    {
        self::assertFalse(MimeTypes::isImage($mimeType));
    }

    /**
     * @param string $mimeType Not existing mime type, e.g. "lorem/ipsum"
     * @dataProvider provideNotExistingMimeType
     */
    public function testIsImageNotExistingMimeType($mimeType)
    {
        self::assertFalse(MimeTypes::isImage($mimeType));
    }

    /**
     * @param mixed $path Empty value, e.g. ""
     * @dataProvider provideEmptyScalarValue
     */
    public function testIsImagePathEmptyPath($path)
    {
        self::assertFalse(MimeTypes::isImagePath($path));
    }

    /**
     * @param string $path    Path of the file to check
     * @param bool   $isImage Expected information if the file is an image
     *
     * @dataProvider provideExistingFilePathToCheckIsImagePath
     */
    public function testIsImagePathExistingPath($path, $isImage)
    {
        self::assertEquals($isImage, MimeTypes::isImagePath($path));
    }

    /**
     * @param mixed $path Path of not existing file, e.g. "lorem/ipsum.jpg"
     * @dataProvider provideNotExistingFilePath
     */
    public function testIsImagePathNotExistingPath($path)
    {
        self::assertFalse(MimeTypes::isImagePath($path));
    }
}
