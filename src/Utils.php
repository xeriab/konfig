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

namespace Exen\Konfig;

final class Utils
{
    /**
     * Includes the given file and returns the results.
     *
     * @param string $file The path to the file
     * @param bool $once Require once or not
     * @return mixed The results of the include
     * @codeCoverageIgnore
     * @since 0.1.0
     */
    public static function load(string $file = null, bool $once = false)
    {
        return ($once ? require_once $file : require $file);
    }

    /**
     * Takes a value and checks if it's a Closure or not, if it's a Closure it
     * will return the result of the closure, if not, it will simply return the
     * value.
     *
     * @param mixed $var The value to get
     * @return mixed
     * @codeCoverageIgnore
     * @since 0.1.0
     */
    public static function checkValue($var = null)
    {
        return ($var instanceof \Closure) ? $var() : $var;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     * @since 0.1.2
     */
    public function __toString()
    {
        return 'Exen\Konfig\Utils' . PHP_EOL;
    }
}

// END OF ./src/Utils.php FILE
