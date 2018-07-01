<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meritoo\Common\Test\Utilities;

use Generator;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Composer;

/**
 * Test case of the useful Composer-related methods
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
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

    /**
     * @param string $composerJsonPath Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetValueNotExistingComposerJson($composerJsonPath)
    {
        self::assertNull(Composer::getValue($composerJsonPath, ''));
        self::assertNull(Composer::getValue('not/existing/composer.json', ''));
    }

    /**
     * @param string $nodeName Empty value, e.g. ""
     * @dataProvider provideEmptyValue
     */
    public function testGetValueNotExistingNode($nodeName)
    {
        self::assertNull(Composer::getValue($this->composerJsonPath, $nodeName));
        self::assertNull(Composer::getValue($this->composerJsonPath, 'not_existing_node'));
    }

    /**
     * @param string $nodeName  Name of existing node
     * @param string $nodeValue Value of existing node
     *
     * @dataProvider getExistingNode
     */
    public function testGetValueExistingNode($nodeName, $nodeValue)
    {
        self::assertEquals($nodeValue, Composer::getValue($this->composerJsonPath, $nodeName));
    }

    /**
     * Provides names and values of existing nodes
     *
     * @return Generator
     */
    public function getExistingNode()
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
    protected function setUp()
    {
        parent::setUp();

        $this->composerJsonPath = $this->getFilePathForTesting(Composer::FILE_NAME_MAIN);
    }
}
