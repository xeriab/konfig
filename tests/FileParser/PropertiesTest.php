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

use Exen\Konfig\FileParser\Properties;

/**
 * Tests for Exen\Konfig\FileParser\Properties.
 *
 * @package Test
 * @subpackage FileParser
 */
class PropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Properties
     */
    protected $properties;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->properties = new Properties();
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
     * @covers Exen\Konfig\FileParser\Properties::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = ['properties'];
        $actual = $this->properties->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Properties::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage Error parsing PROPERTIES file
     */
    public function testLoadInvalidProperties()
    {
        $this->properties->parse(__DIR__ . '/../mocks/fail/error.properties');
    }

    /**
     * @covers Exen\Konfig\FileParser\Properties::parse()
     */
    public function testLoadProperties()
    {
        $actual = $this->properties->parse(__DIR__ . '/../mocks/pass/config.properties');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

// END OF ./tests/FileParser/PropertiesTest.php FILE
