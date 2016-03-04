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

$OS = strtoupper(substr(PHP_OS, 0, 3));

if (!defined('IS_WIN')) {
    define('IS_WIN', ($OS === 'WIN') ? true : false);
} // END if

if (!defined('IS_UNIX')) {
    define('IS_UNIX', (IS_WIN === false) ? true : false);
} // END if

unset($OS);

if (IS_WIN) {
    if (!defined('DS')) {
        define('DS', '\\');
    } // END if
} else {
    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    } // END if
} // END if

if (!defined('PS')) {
    define('PS', '/');
} // END if

#:---

if (!function_exists('_fixPath')) {
    function _fixPath($path = null)
    {
        $_path = null;

        if (IS_WIN) {
            $_path = str_replace(DS, '\\', $path);
            $_path = str_replace('/', '\\', $path);
        } else {
            $_path = str_replace('\\', DS, $path);
        } // END if

        return $_path;
    } // END OF _fixPath FUNCTION
} // END if

#:---

// DEFINES
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', _fixPath(realpath(__DIR__ . '../../') . DS));
}

if (!defined('KONFIG_TEST_MOCKS')) {
    define('KONFIG_TEST_MOCKS', _fixPath(ROOT_DIR . 'tests' . DS . 'mocks' . DS));
}

if (!defined('KONFIG_TEST_FILES')) {
    define('KONFIG_TEST_FILES', _fixPath(ROOT_DIR . 'tests' . DS . 'test_configs' . DS));
}

#:---

// require_once ROOT_DIR . 'vendor' . DS . 'autoload.php';

#: END OF ./tests/bootstrap.php FILE
