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

namespace Exen\Konfig\Test\Fixture;

use Exen\Konfig\AbstractKonfig;

class SimpleKonfig extends AbstractKonfig
{
    protected function getDefaults()
    {
        return array(
            'host' => 'localhost',
            'port' => 80,
            'servers' => array(
                'host1',
                'host2',
                'host3',
            ),
            'application' => array(
                'name' => 'konfig',
                'secret' => 'secret',
            ),
        );
    }
}

#: END OF ./tests/Fixture/SimpleKonfig.php FILE
