<?php
/**
 * Konfig
 *
 * Yet another simple configuration file loader library.
 *
 * @author  Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link    https://xeriab.github.io/projects/konfig
 */

namespace Exen\Konfig\Test;

use Exen\Konfig\Konfig;

/**
 * Tests for Exen\Konfig\Konfig.
 *
 * @package Test
 * @subpackage Konfig
 */
class KonfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Konfig
     */
    protected $konfig;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // Nothing to put here
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        // Nothing to put here
    }

    /**
     * @covers Exen\Konfig\Konfig::load()
     * @covers Exen\Konfig\Konfig::getParser()
     * @expectedException Exen\Konfig\Exception\UnsupportedFileFormatException
     * @expectedExceptionMessage Unsupported configuration format
     */
    public function testLoadWithUnsupportedFormat()
    {
        $konfig = Konfig::load(__DIR__ . '/mocks/' . 'fail/error.lib');
        // $this->markTestIncomplete('Not yet implemented');
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @expectedException Exen\Konfig\Exception\UnsupportedFileFormatException
     * @expectedExceptionMessage Unsupported configuration format
     */
    public function testConstructWithUnsupportedFormat()
    {
        $konfig = new Konfig(__DIR__ . '/mocks/' . 'fail/error.lib');
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     * @expectedException Exen\Konfig\Exception\FileNotFoundException
     * @expectedExceptionMessage Configuration file: [justAdumbAssText] cannot be found
     */
    public function testConstructWithInvalidPath()
    {
        $konfig = new Konfig('justAdumbAssText');
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     * @expectedException Exen\Konfig\Exception\EmptyDirectoryException
     */
    public function testConstructWithEmptyDirectory()
    {
        $konfig = new Konfig(__DIR__ . '/mocks/' . 'empty');
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     */
    public function testConstructWithArray()
    {
        $paths  = [__DIR__ . '/mocks/' . 'pass/config.xml', __DIR__ . '/mocks/' . 'pass/config2.json'];
        $konfig = new Konfig($paths);

        $expected = 'localhost';
        $actual   = $konfig->get('host');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     * @expectedException Exen\Konfig\Exception\FileNotFoundException
     */
    public function testConstructWithArrayWithNonexistentFile()
    {
        $paths  = [__DIR__ . '/mocks/' . 'pass/config.xml', __DIR__ . '/mocks/' . 'pass/config3.json'];
        $konfig = new Konfig($paths);

        $expected = 'localhost';
        $actual   = $konfig->get('host');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     */
    public function testConstructWithArrayWithOptionalFile()
    {
        $paths  = [__DIR__ . '/mocks/' . 'pass/config.xml', '?' . __DIR__ . '/mocks/' . 'pass/config2.json'];
        $konfig = new Konfig($paths);

        $expected = 'localhost';
        $actual   = $konfig->get('host');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     */
    public function testConstructWithArrayWithOptionalNonexistentFile()
    {
        $paths  = [__DIR__ . '/mocks/' . 'pass/config.xml', '?' . __DIR__ . '/mocks/' . 'pass/config3.json'];
        $konfig = new Konfig($paths);

        $expected = 'localhost';
        $actual   = $konfig->get('host');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     */
    public function testConstructWithDirectory()
    {
        $konfig = new Konfig(__DIR__ . '/mocks/' . 'dir');

        $expected = 'localhost';
        $actual   = $konfig->get('host');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     */
    public function testConstructWithYml()
    {
        $konfig = new Konfig(__DIR__ . '/mocks/' . 'pass/config.yml');

        $expected = 'localhost';
        $actual   = $konfig->get('host');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     */
    public function testConstructWithYmlDist()
    {
        $konfig = new Konfig(__DIR__ . '/mocks/' . 'pass/config.yml.dist');

        $expected = 'localhost';
        $actual   = $konfig->get('host');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::getParser()
     * @covers Exen\Konfig\Konfig::getValidPath()
     */
    public function testConstructWithEmptyYml()
    {
        $konfig = new Konfig(__DIR__ . '/mocks/' . 'pass/empty.yaml');

        $expected = [];
        $actual   = $konfig->all();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\Konfig::__construct()
     * @covers Exen\Konfig\Konfig::get()
     * @dataProvider specialConfigProvider()
     */
    public function testGetReturnsArrayMergedArray($konfig)
    {
        $this->assertCount(4, $konfig->get('servers'));
    }

    /**
     * Provides names of example configuration files
     */
    public function configProvider()
    {
        return array_merge(
            [
                [new Konfig(__DIR__ . '/mocks/' . 'pass/config-exec.php')],
                [new Konfig(__DIR__ . '/mocks/' . 'pass/config.ini')],
                [new Konfig(__DIR__ . '/mocks/' . 'pass/config.json')],
                [new Konfig(__DIR__ . '/mocks/' . 'pass/config.php')],
                [new Konfig(__DIR__ . '/mocks/' . 'pass/config.xml')],
                [new Konfig(__DIR__ . '/mocks/' . 'pass/config.yaml')],
            ]
        );
    }

    /**
     * Provides names of example configuration files (for array and directory)
     */
    public function specialConfigProvider()
    {
        return [
            [
                new Konfig(
                    [
                        __DIR__ . '/mocks/' . 'pass/config2.json',
                        __DIR__ . '/mocks/' . 'pass/config.yaml',
                    ]
                ),
                new Konfig(__DIR__ . '/mocks/' . 'dir/'),
            ],
        ];
    }
}

#: END OF ./tests/KonfigTest.php FILE
