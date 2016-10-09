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

use Exen\Konfig\FileParser\Yaml;

/**
 * Tests for Exen\Konfig\FileParser\Yaml.
 *
 * @package Test
 * @subpackage FileParser
 */
class YamlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Yaml
     */
    protected $yaml;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->yaml = new Yaml();
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
     * @covers Exen\Konfig\FileParser\Yaml::getSupportedFileExtensions()
     */
    public function testGetSupportedFileExtensions()
    {
        $expected = ['yaml', 'yml'];
        $actual = $this->yaml->getSupportedFileExtensions();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Exen\Konfig\FileParser\Yaml::parse()
     * @expectedException Exen\Konfig\Exception\ParseException
     * @expectedExceptionMessage Error parsing YAML file
     */
    public function testLoadInvalidYaml()
    {
        $this->yaml->parse(__DIR__ . '/../mocks/fail/error.yaml');
    }

    /**
     * @covers Exen\Konfig\FileParser\Yaml::parse()
     */
    public function testLoadYaml()
    {
        $actual = $this->yaml->parse(__DIR__ . '/../mocks/pass/config.yml');
        $this->assertEquals('localhost', $actual['host']);
        $this->assertEquals('80', $actual['port']);
    }
}

#: END OF ./tests/FileParser/YamlTest.php FILE
