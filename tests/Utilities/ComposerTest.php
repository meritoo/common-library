<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Test\Common\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Composer;

/**
 * Test case of the useful Composer-related methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers \Meritoo\Common\Utilities\Composer
 */
class ComposerTest extends BaseTestCase
{
    /**
     * Path of existing composer.json used as source of data for tests
     *
     * @var string
     */
    private $composerJsonPath;

    public function testConstructor()
    {
        static::assertHasNoConstructor(Composer::class);
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

    /**
     * @param string $nodeName  Name of existing node
     * @param string $nodeValue Value of existing node
     *
     * @dataProvider getExistingNode
     */
    public function testGetValueExistingNode(string $nodeName, string $nodeValue): void
    {
        self::assertEquals($nodeValue, Composer::getValue($this->composerJsonPath, $nodeName));
    }

    /**
     * Provides names and values of existing nodes
     *
     * @return Generator
     */
    public function getExistingNode(): Generator
    {
        yield[
            'name',
            'test/test',
        ];

        yield[
            'version',
            '1.0.2',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->composerJsonPath = $this->getFilePathForTesting(Composer::FILE_NAME_MAIN);
    }
}
