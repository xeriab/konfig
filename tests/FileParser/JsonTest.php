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

namespace Exen\Konfig\Test\FileParser;

use Exen\Konfig\FileParser\Json;

/**
 * Tests for Exen\Konfig\FileParser\Json.
 *
 * @package Test
 * @subpackage FileParser
 */
class JsonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Json
     */
    protected $json;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->json = new Json();
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
     * @covers Exen\Konfig\FileParser\Json::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = array('json');
        $actual = $this->json->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Json::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage syntax error, unexpected $end, expecting ']'
     */
    public function testLoadInvalidIni()
    {
        $this->json->parse(__DIR__ . '/../mocks/fail/error.json');
    }

    /**
     * @covers Exen\Konfig\FileParser\Json::parse()
     */
    public function testLoadIni()
    {
        $actual = $this->json->parse(__DIR__ . '/../mocks/pass/config.json');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

#: END OF ./tests/FileParser/JsonTest.php FILE
