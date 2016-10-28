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

// DEFINES
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', realpath(dirname(__FILE__) . '/../') . DIRECTORY_SEPARATOR);
}

print_r(ROOT_DIR);

if (!defined('KONFIG_TEST_MOCKS')) {
    define('KONFIG_TEST_MOCKS', ROOT_DIR . 'tests' . DIRECTORY_SEPARATOR . 'mocks' . DIRECTORY_SEPARATOR);
}

if (!defined('KONFIG_TEST_FILES')) {
    define('KONFIG_TEST_FILES', ROOT_DIR . 'tests' . DIRECTORY_SEPARATOR . 'test_configs' . DIRECTORY_SEPARATOR);
}

//---

// print_r(ROOT_DIR, 1);

require 'vendor/autoload.php';

// END OF ./tests/bootstrap.php FILE
