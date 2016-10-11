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

use Exen\Konfig\FileParser\Neon;

/**
 * Tests for Exen\Konfig\FileParser\Neon.
 *
 * @package Test
 * @subpackage FileParser
 */
class NeonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Neon
     */
    protected $neon;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->neon = new Neon();
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
     * @covers Exen\Konfig\FileParser\Neon::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = ['neon'];
        $actual = $this->neon->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Neon::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage Error parsing NEON file
     */
    public function testLoadInvalidNeon()
    {
        $this->neon->parse(__DIR__ . '/../mocks/fail/error.neon');
    }

    /**
     * @covers Exen\Konfig\FileParser\Neon::parse()
     */
    public function testLoadNeon()
    {
        $actual = $this->neon->parse(__DIR__ . '/../mocks/pass/config.neon');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

// END OF ./tests/FileParser/NeonTest.php FILE
