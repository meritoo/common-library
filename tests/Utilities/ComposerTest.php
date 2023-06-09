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
use Meritoo\Common\Utilities\Composer;
use Meritoo\Common\Utilities\Miscellaneous;
use Meritoo\Common\Utilities\Regex;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Composer::class)]
#[UsesClass(BaseTestCaseTrait::class)]
#[UsesClass(Arrays::class)]
#[UsesClass(Miscellaneous::class)]
#[UsesClass(Regex::class)]
class ComposerTest extends BaseTestCase
{
    private string $composerJsonPath;

    public static function getExistingNode(): Generator
    {
        yield [
            'name',
            'test/test',
        ];

        yield [
            'version',
            '1.0.2',
        ];
    }

    public function testConstructor(): void
    {
        static::assertHasNoConstructor(Composer::class);
    }

    #[DataProvider('getExistingNode')]
    public function testGetValueExistingNode(string $nodeName, string $nodeValue): void
    {
        self::assertEquals($nodeValue, Composer::getValue($this->composerJsonPath, $nodeName));
    }

    public function testGetValueNotExistingComposerJson(): void
    {
        self::assertNull(Composer::getValue('', ''));
        self::assertNull(Composer::getValue('not/existing/composer.json', ''));
    }

    public function testGetValueNotExistingNode(): void
    {
        self::assertNull(Composer::getValue($this->composerJsonPath, ''));
        self::assertNull(Composer::getValue($this->composerJsonPath, 'not_existing_node'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->composerJsonPath = self::getFilePathForTesting(Composer::FILE_NAME_MAIN);
    }
}
