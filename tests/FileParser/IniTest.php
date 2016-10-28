<?php

/**
 * Konfig
 *
 * Yet another simple configuration loader library.
 *
 * @author  Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link    https://xeriab.github.io/projects/konfig
 */

namespace Exen\Konfig\Test\FileParser;

use Exen\Konfig\FileParser\Ini;

/**
 * Tests for Exen\Konfig\FileParser\Ini.
 *
 * @package Test
 * @subpackage FileParser
 */
class IniTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ini
     */
    protected $ini;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->ini = new Ini();
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
     * @covers Exen\Konfig\FileParser\Ini::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = ['ini', 'cfg'];
        $actual = $this->ini->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Ini::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage syntax error, unexpected $end, expecting ']'
     */
    public function testLoadInvalidIni()
    {
        $this->ini->parse(KONFIG_TEST_MOCKS . 'fail/error.ini');
    }

    /**
     * @covers Exen\Konfig\FileParser\Ini::parse()
     */
    public function testLoadIni()
    {
        $actual = $this->ini->parse(KONFIG_TEST_MOCKS . 'pass/config.ini');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

// END OF ./tests/FileParser/IniTest.php FILE
