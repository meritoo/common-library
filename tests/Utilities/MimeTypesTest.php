<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\MimeTypes;

/**
 * Test case of the useful methods for mime types of files
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class MimeTypesTest extends BaseTestCase
{
    public function testConstructor()
    {
        static::assertHasNoConstructor(MimeTypes::class);
    }

    /**
     * @param mixed $mimeType Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetExtensionEmptyMimeType($mimeType)
    {
        self::assertEquals('', MimeTypes::getExtension($mimeType));
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
     * @dataProvider provideMimesTypesToGetExtensions
     */
    public function testGetExtensions($mimesTypes, $extensions)
    {
        self::assertEquals($extensions, MimeTypes::getExtensions($mimesTypes));
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
     * @dataProvider provideEmptyValue
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
     * @dataProvider provideEmptyValue
     */
    public function testIsImageEmptyMimeType($mimeType)
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
     * @param string $mimeType Mime type of non-image, e.g. "text/plain"
     * @dataProvider provideNonImageMimeType
     */
    public function testIsImageNonImageMimeType($mimeType)
    {
        self::assertFalse(MimeTypes::isImage($mimeType));
    }

    /**
     * @param mixed $path Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testIsImagePathEmptyPath($path)
    {
        self::assertFalse(MimeTypes::isImagePath($path));
    }

    /**
     * @param mixed $path Path of not existing file, e.g. "lorem/ipsum.jpg"
     * @dataProvider provideNotExistingFilePath
     */
    public function testIsImagePathNotExistingPath($path)
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
     * @param string $mimeType Mime type of image, e.g. "image/jpeg"
     * @dataProvider provideImageMimeType
     */
    public function testIsImageImageMimeType($mimeType)
    {
        self::assertTrue(MimeTypes::isImage($mimeType));
    }

    /**
     * Provides not existing mime type
     *
     * @return Generator
     */
    public function provideNotExistingMimeType()
    {
        yield['lorem/ipsum'];
        yield['dolor'];
        yield['x/y/z'];
    }

    /**
     * Provides mime type of non-image
     *
     * @return Generator
     */
    public function provideNonImageMimeType()
    {
        yield['application/rtf'];
        yield['audio/mp4'];
        yield['text/plain'];
        yield['text/html'];
    }

    /**
     * Provides mime type of image
     *
     * @return Generator
     */
    public function provideImageMimeType()
    {
        yield['image/bmp'];
        yield['image/jpeg'];
        yield['image/png'];
        yield['image/tiff'];
        yield['image/vnd.microsoft.icon'];
        yield['image/x-rgb'];
    }

    /**
     * Provides existing mime type used to get single, one extension
     *
     * @return Generator
     */
    public function provideMimeTypeToGetSingleExtension()
    {
        yield[
            'application/x-7z-compressed',
            '7z',
        ];

        yield[
            'application/json',
            'json',
        ];

        yield[
            'application/zip',
            'zip',
        ];
    }

    /**
     * Provides existing mime type used to get multiple, more than one extension
     *
     * @return Generator
     */
    public function provideMimeTypeToGetMultipleExtension()
    {
        yield[
            'application/postscript',
            [
                'ai',
                'eps',
                'ps',
            ],
        ];

        yield[
            'audio/midi',
            [
                'mid',
                'midi',
                'kar',
                'rmi',
            ],
        ];

        yield[
            'image/jpeg',
            [
                'jpeg',
                'jpe',
                'jpg',
            ],
        ];

        yield[
            'text/html',
            [
                'html',
                'htm',
            ],
        ];

        yield[
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

        yield[
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
     * Provides not existing mime types
     *
     * @return Generator
     */
    public function provideNotExistingMimeTypes()
    {
        yield[
            [],
        ];

        yield[
            [
                '',
                null,
                false,
                0,
            ],
        ];

        yield[
            [
                'lorem/ipsum',
                'dolor/sit',
            ],
        ];
    }

    /**
     * Provides mime types used to get extensions
     *
     * @return Generator
     */
    public function provideMimesTypesToGetExtensions()
    {
        yield[
            [
                'application/x-7z-compressed',
                'application/json',
            ],
            [
                'application/x-7z-compressed' => '7z',
                'application/json'            => 'json',
            ],
        ];

        yield[
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
                'application/xml'         => [
                    'xml',
                    'xsl',
                ],
                'audio/mp4'               => 'mp4a',
                'video/mp4'               => [
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
    public function provideMimesTypesToGetExtensionsUpperCase()
    {
        yield[
            [
                'application/x-7z-compressed',
                'application/json',
            ],
            [
                'application/x-7z-compressed' => '7Z',
                'application/json'            => 'JSON',
            ],
        ];

        yield[
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
                'audio/mp4'       => 'MP4A',
                'text/html'       => [
                    'HTML',
                    'HTM',
                ],
                'video/mp4'       => [
                    'MP4',
                    'MP4V',
                    'MPG4',
                    'M4V',
                ],
            ],
        ];
    }

    /**
     * Provides real file path to get mime type
     *
     * @return Generator
     */
    public function provideFilePathToGetMimeTypeOfRealFile()
    {
        yield[
            $this->getFilePathForTesting('minion.jpg'),
            'image/jpeg',
        ];

        yield[
            $this->getFilePathForTesting('lorem-ipsum.txt'),
            'text/plain',
        ];
    }

    /**
     * Provides real file path to get information if the file is an image
     *
     * @return Generator
     */
    public function provideExistingFilePathToCheckIsImagePath()
    {
        yield[
            $this->getFilePathForTesting('minion.jpg'),
            true,
        ];

        yield[
            $this->getFilePathForTesting('lorem-ipsum.txt'),
            false,
        ];
    }
}
