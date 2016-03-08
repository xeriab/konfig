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

use Exen\Konfig\FileParser\Php;

/**
 * Tests for Exen\Konfig\FileParser\Php.
 *
 * @package Test
 * @subpackage FileParser
 */
class PhpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Php
     */
    protected $php;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->php = new Php();
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
     * @covers Exen\Konfig\FileParser\Php::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = ['php', 'inc'];
        $actual   = $this->php->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Php::parse()
     * @expectedException Exen\Konfig\Exception\UnsupportedFileFormatException
     * @expectedExceptionMessage PHP file does not return an array
     */
    public function testLoadInvalidPhp()
    {
        $this->php->parse(__DIR__ . '/../mocks/fail/error.php');
    }

    /**
     * @covers Exen\Konfig\FileParser\Php::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage PHP file threw an exception
     */
    public function testLoadExceptionalPhp()
    {
        $this->php->parse(__DIR__ . '/../mocks/fail/error-exception.php');
    }

    /**
     * @covers Exen\Konfig\FileParser\Php::parse()
     */
    public function testLoadPhpArray()
    {
        $actual = $this->php->parse(__DIR__ . '/../mocks/pass/config.php');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }

    /**
     * @covers Exen\Konfig\FileParser\Php::parse()
     */
    public function testLoadPhpCallable()
    {
        $actual = $this->php->parse(__DIR__ . '/../mocks/pass/config-exec.php');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

#: END OF ./tests/FileParser/PhpTest.php FILE
