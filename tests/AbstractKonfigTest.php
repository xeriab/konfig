<?php
/**
 * Konfig
 *
 * Yet another simple konfig file loader library.
 *
 * @author  Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link    https://xeriab.github.io/projects/konfig
 */

namespace Exen\Konfig\Test;

use Exen\Konfig\AbstractKonfig;
use Exen\Konfig\Test\Fixture\SimpleKonfig;

/**
 * Tests for Exen\Konfig\AbstractKonfig.
 *
 * @package Test
 * @subpackage AbstractKonfig
 */
class AbstractKonfigTest extends \PHPUnit_Framework_TestCase
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
        $this->konfig = new SimpleKonfig(
            [
                'host'    => 'localhost',
                'port'    => 80,
                'servers' => [
                    'host1',
                    'host2',
                    'host3',
                ],
                'app'     => [
                    'name'    => 'konfig',
                    'secret'  => 'secret',
                    'runtime' => null,
                ],
                'user'    => null,
            ]
        );
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
     * @covers Exen\Konfig\AbstractKonfig::__construct()
     * @covers Exen\Konfig\AbstractKonfig::getDefaults()
     */
    public function testDefaultOptionsSetOnInstantiation()
    {
        $konfig = new SimpleKonfig(
            array(
                'host' => 'localhost',
                'port' => 80,
            )
        );

        $this->assertEquals('localhost', $konfig->get('host'));
        $this->assertEquals(80, $konfig->get('port'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::get()
     */
    public function testGet()
    {
        $this->assertEquals('localhost', $this->konfig->get('host'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::get()
     */
    public function testGetWithDefaultValue()
    {
        $this->assertEquals(128, $this->konfig->get('ttl', 128));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::get()
     */
    public function testGetNestedKey()
    {
        $this->assertEquals('konfig', $this->konfig->get('app.name'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::get()
     */
    public function testGetNestedKeyWithDefaultValue()
    {
        $this->assertEquals(128, $this->konfig->get('app.ttl', 128));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::get()
     */
    public function testGetNonexistentKey()
    {
        $this->assertNull($this->konfig->get('proxy'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::get()
     */
    public function testGetNonexistentNestedKey()
    {
        $this->assertNull($this->konfig->get('proxy.name'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::get()
     */
    public function testGetReturnsArray()
    {
        $this->assertArrayHasKey('name', $this->konfig->get('app'));
        $this->assertEquals('konfig', $this->konfig->get('app.name'));
        $this->assertCount(3, $this->konfig->get('app'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::set()
     */
    public function testSet()
    {
        $this->konfig->set('region', 'apac');
        $this->assertEquals('apac', $this->konfig->get('region'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::set()
     */
    public function testSetNestedKey()
    {
        $this->konfig->set('location.country', 'Singapore');
        $this->assertEquals('Singapore', $this->konfig->get('location.country'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::set()
     */
    public function testSetArray()
    {
        $this->konfig->set('database', array(
            'host' => 'localhost',
            'name' => 'mydatabase',
        ));
        $this->assertTrue(is_array($this->konfig->get('database')));
        $this->assertEquals('localhost', $this->konfig->get('database.host'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::set()
     */
    public function testCacheWithNestedArray()
    {
        $this->konfig->set('database', array(
            'host' => 'localhost',
            'name' => 'mydatabase',
        ));
        $this->assertTrue(is_array($this->konfig->get('database')));
        $this->konfig->set('database.host', '127.0.0.1');
        $expected = array(
            'host' => '127.0.0.1',
            'name' => 'mydatabase',
        );
        $this->assertEquals($expected, $this->konfig->get('database'));

        $this->konfig->set('config', array(
            'database' => array(
                'host' => 'localhost',
                'name' => 'mydatabase',
            ),
        ));
        $this->konfig->get('config'); //Just get to set related cache
        $this->konfig->get('config.database'); //Just get to set related cache

        $this->konfig->set('config.database.host', '127.0.0.1');
        $expected = array(
            'database' => array(
                'host' => '127.0.0.1',
                'name' => 'mydatabase',
            ),
        );
        $this->assertEquals($expected, $this->konfig->get('config'));

        $expected = array(
            'host' => '127.0.0.1',
            'name' => 'mydatabase',
        );
        $this->assertEquals($expected, $this->konfig->get('config.database'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::set()
     */
    public function testCacheWithNestedMiddleArray()
    {
        $this->konfig->set('config', array(
            'database' => array(
                'host' => 'localhost',
                'name' => 'mydatabase',
            ),
        ));

        $this->konfig->get('config'); // Just get to set related cache
        $this->konfig->get('config.database'); // Just get to set related cache
        $this->konfig->get('config.database.host'); // Just get to set related cache
        $this->konfig->get('config.database.name'); // Just get to set related cache

        $this->konfig->set('config.database', array(
            'host' => '127.0.0.1',
            'name' => 'mynewdatabase',
        ));
        $this->assertEquals('127.0.0.1', $this->konfig->get('config.database.host'));
        $this->assertEquals('mynewdatabase', $this->konfig->get('config.database.name'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::set()
     */
    public function testSetAndUnsetArray()
    {
        $this->konfig->set('database', array(
            'host' => 'localhost',
            'name' => 'mydatabase',
        ));
        $this->assertTrue(is_array($this->konfig->get('database')));
        $this->assertEquals('localhost', $this->konfig->get('database.host'));
        $this->konfig->set('database.host', null);
        $this->assertNull($this->konfig->get('database.host'));
        $this->konfig->set('database', null);
        $this->assertNull($this->konfig->get('database'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::has()
     */
    public function testHas()
    {
        $this->assertTrue($this->konfig->has('app'));
        $this->assertTrue($this->konfig->has('user'));
        $this->assertFalse($this->konfig->has('not_exist'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::has()
     */
    public function testHasNestedKey()
    {
        $this->assertTrue($this->konfig->has('app.name'));
        $this->assertTrue($this->konfig->has('app.runtime'));
        $this->assertFalse($this->konfig->has('app.not_exist'));
        $this->assertFalse($this->konfig->has('not_exist.name'));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::all()
     */
    public function testAll()
    {
        $all = [
            'host'    => 'localhost',
            'port'    => 80,
            'servers' => [
                'host1',
                'host2',
                'host3',
            ],
            'app'     => [
                'name'    => 'konfig',
                'secret'  => 'secret',
                'runtime' => null,
            ],
            'user'    => null,
        ];

        $this->assertEquals($all, $this->konfig->all());
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::offsetGet()
     */
    public function testOffsetGet()
    {
        $this->assertEquals('localhost', $this->konfig['host']);
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::offsetGet()
     */
    public function testOffsetGetNestedKey()
    {
        $this->assertEquals('konfig', $this->konfig['app.name']);
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::offsetExists()
     */
    public function testOffsetExists()
    {
        $this->assertTrue(isset($this->konfig['host']));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::offsetExists()
     */
    public function testOffsetExistsReturnsFalseOnNonexistentKey()
    {
        $this->assertFalse(isset($this->konfig['database']));
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::offsetSet()
     */
    public function testOffsetSet()
    {
        $this->konfig['newkey'] = 'newvalue';
        $this->assertEquals('newvalue', $this->konfig['newkey']);
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::offsetUnset()
     */
    public function testOffsetUnset()
    {
        unset($this->konfig['app']);
        $this->assertNull($this->konfig['app']);
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::current()
     */
    public function testCurrent()
    {
        /* Reset to the beginning of the test config */
        $this->konfig->rewind();
        $this->assertEquals($this->konfig['host'], $this->konfig->current());

        /* Step through each of the other elements of the test config */
        $this->konfig->next();
        $this->assertEquals($this->konfig['port'], $this->konfig->current());
        $this->konfig->next();
        $this->assertEquals($this->konfig['servers'], $this->konfig->current());
        $this->konfig->next();
        $this->assertEquals($this->konfig['app'], $this->konfig->current());
        $this->konfig->next();
        $this->assertEquals($this->konfig['user'], $this->konfig->current());

        /* Step beyond the end and confirm the result */
        $this->konfig->next();
        $this->assertFalse($this->konfig->current());
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::key()
     */
    public function testKey()
    {
        /* Reset to the beginning of the test config */
        $this->konfig->rewind();
        $this->assertEquals('host', $this->konfig->key());

        /* Step through each of the other elements of the test config */
        $this->konfig->next();
        $this->assertEquals('port', $this->konfig->key());
        $this->konfig->next();
        $this->assertEquals('servers', $this->konfig->key());
        $this->konfig->next();
        $this->assertEquals('app', $this->konfig->key());
        $this->konfig->next();
        $this->assertEquals('user', $this->konfig->key());

        /* Step beyond the end and confirm the result */
        $this->konfig->next();
        $this->assertNull($this->konfig->key());
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::next()
     */
    public function testNext()
    {
        /* Reset to the beginning of the test config */
        $this->konfig->rewind();

        /* Step through each of the other elements of the test config */
        $this->assertEquals($this->konfig['port'], $this->konfig->next());
        $this->assertEquals($this->konfig['servers'], $this->konfig->next());
        $this->assertEquals($this->konfig['app'], $this->konfig->next());
        $this->assertEquals($this->konfig['user'], $this->konfig->next());

        /* Step beyond the end and confirm the result */
        $this->assertFalse($this->konfig->next());
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::rewind()
     */
    public function testRewind()
    {
        /* Rewind from somewhere out in the array */
        $this->konfig->next();
        $this->konfig->next();
        $this->assertEquals($this->konfig['host'], $this->konfig->rewind());

        /* Rewind again from the beginning of the array */
        $this->assertEquals($this->konfig['host'], $this->konfig->rewind());
    }

    /**
     * @covers Exen\Konfig\AbstractKonfig::valid()
     */
    public function testValid()
    {
        /* Reset to the beginning of the test config */
        $this->konfig->rewind();
        $this->assertTrue($this->konfig->valid());

        /* Step through each of the other elements of the test config */
        $this->konfig->next();
        $this->assertTrue($this->konfig->valid());
        $this->konfig->next();
        $this->assertTrue($this->konfig->valid());
        $this->konfig->next();
        $this->assertTrue($this->konfig->valid());
        $this->konfig->next();
        $this->assertTrue($this->konfig->valid());

        /* Step beyond the end and confirm the result */
        $this->konfig->next();
        $this->assertFalse($this->konfig->valid());
    }

    /**
     * Tests to verify that Iterator is properly implemented by using a foreach
     * loop on the test config
     */
    public function testIterator()
    {
        /* Create numerically indexed copies of the test config */
        $expectedKeys   = ['host', 'port', 'servers', 'app', 'user'];
        $expectedValues = [
            'localhost',
            80,
            ['host1', 'host2', 'host3'],
            [
                'name'    => 'konfig',
                'secret'  => 'secret',
                'runtime' => null,
            ],
            null,
        ];

        $idxKonfig = 0;

        foreach ($this->konfig as $konfigKey => $konfigValue) {
            $this->assertEquals($expectedKeys[$idxKonfig], $konfigKey);
            $this->assertEquals($expectedValues[$idxKonfig], $konfigValue);
            $idxKonfig++;
        }
    }

    public function testGetShouldNotSet()
    {
        $this->konfig->get('invalid', 'default');
        $actual = $this->konfig->get('invalid', 'expected');
        $this->assertSame('expected', $actual);
    }
}

#: END OF ./tests/AbstractKonfigTest.php FILE
