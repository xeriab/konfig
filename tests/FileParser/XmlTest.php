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

use Exen\Konfig\FileParser\Xml;

/**
 * Tests for Exen\Konfig\FileParser\Xml.
 *
 * @package Test
 * @subpackage FileParser
 */
class XmlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Xml
     */
    protected $xml;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->xml = new Xml();
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
     * @covers Exen\Konfig\FileParser\Xml::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = ['xml'];
        $actual = $this->xml->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Xml::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage Opening and ending tag mismatch: name line 4
     */
    public function testLoadInvalidXml()
    {
        $this->xml->parse(__DIR__ . '/../mocks/fail/error.xml');
    }

    /**
     * @covers Exen\Konfig\FileParser\Xml::parse()
     */
    public function testLoadXml()
    {
        $actual = $this->xml->parse(__DIR__ . '/../mocks/pass/config.xml');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

// END OF ./tests/FileParser/XmlTest.php FILE
