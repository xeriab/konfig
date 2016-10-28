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

use Exen\Konfig\FileParser\Toml;

/**
 * Tests for Exen\Konfig\FileParser\Toml.
 *
 * @package Test
 * @subpackage FileParser
 */
class TomlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Toml
     */
    protected $toml;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->toml = new Toml();
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
     * @covers Exen\Konfig\FileParser\Toml::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = ['toml'];
        $actual = $this->toml->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Toml::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage Error parsing TOML file
     */
    public function testLoadInvalidToml()
    {
        $this->toml->parse(KONFIG_TEST_MOCKS . 'fail/error.toml');
    }

    /**
     * @covers Exen\Konfig\FileParser\Toml::parse()
     */
    public function testLoadToml()
    {
        $actual = $this->toml->parse(KONFIG_TEST_MOCKS . 'pass/config.toml');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

// END OF ./tests/FileParser/TomlTest.php FILE
