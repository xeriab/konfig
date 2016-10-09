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

namespace Exen\Konfig\Test\Fixture;

use Exen\Konfig\AbstractKonfig;

class SimpleKonfig extends AbstractKonfig
{
    protected function getDefaults()
    {
        return [
            'host' => 'localhost',
            'port' => 80,
            'servers' => [
                'host1',
                'host2',
                'host3',
            ],
            'app' => [
                'name' => 'konfig',
                'secret' => 'secret',
            ],
        ];
    }
}

#: END OF ./tests/Fixture/SimpleKonfig.php FILE
