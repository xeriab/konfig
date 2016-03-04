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

namespace Exen\Konfig;

use ArrayAccess;
use InvalidArgumentException;

class Utils
{
    /**
     * Includes the given file and returns the results.
     *
     * @param string The path to the file
     * @return mixed The results of the include
     */
    public static function load($file = null)
    {
        return require_once $file;
    }

    /**
     * Takes a value and checks if it is a Closure or not, if it is it
     * will return the result of the closure, if not, it will simply return the
     * value.
     *
     * @param mixed $var The value to arrGet
     * @return mixed
     */
    public static function value($var = null)
    {
        return ($var instanceof \Closure) ? $var() : $var;
    }

    /**
     * Gets a dot-notated key from an array, with a default value if it does
     * not exist.
     *
     * @param array $array The search array
     * @param mixed $key The dot-notated key or array of keys
     * @param string $default The default value
     * @return mixed
     */
    public static function arrGet($array, $key, $default = null)
    {
        if (!is_array($array) and !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
        }

        if (is_null($key)) {
            return $array;
        }

        if (is_array($key)) {
            $return = [];

            foreach ($key as $k) {
                $return[$k] = static::arrGet($array, $k, $default);
            }

            return $return;
        }

        foreach (explode('.', $key) as $key_part) {
            if (($array instanceof ArrayAccess and isset($array[$key_part])) === false) {
                if (!is_array($array) or !array_key_exists($key_part, $array)) {
                    return static::value($default);
                }
            }

            $array = $array[$key_part];
        }

        return $array;
    }

    /**
     * Set an array item (dot-notated) to the value.
     *
     * @param array $array The array to insert it into
     * @param mixed $key The dot-notated key to set or array of keys
     * @param mixed $value The value
     * @return void
     */
    public static function arrSet(&$array, $key, $value = null)
    {
        if (is_null($key)) {
            $array = $value;
            return;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                static::arrSet($array, $k, $v);
            }
        } else {
            $keys = explode('.', $key);

            while (count($keys) > 1) {
                $key = array_shift($keys);

                if (!isset($array[$key]) or !is_array($array[$key])) {
                    $array[$key] = array();
                }

                $array = &$array[$key];
            }

            $array[array_shift($keys)] = $value;
        }
    }

    /**
     * Merge two arrays recursively, differs in two important ways from array_merge_recursive()
     * - When there's two different values and not both arrays, the latter value overwrites the earlier
     *   instead of merging both into an array
     * - Numeric keys that don't conflict aren't changed, only when a numeric key already exists is the
     *   value added using array_push()
     *
     * @param array multiple variables all of which must be arrays
     * @return array
     * @throws InvalidArgumentException
     */
    public static function arrMerge()
    {
        $array = func_get_arg(0);
        $arrays = array_slice(func_get_args(), 1);

        if (!is_array($array)) {
            throw new InvalidArgumentException('Exen\Konfig\Utils::arrMerge() - all arguments must be arrays.');
        }

        foreach ($arrays as $arr) {
            if (!is_array($arr)) {
                throw new InvalidArgumentException('Exen\Konfig\Utils::arrMerge() - all arguments must be arrays.');
            }

            foreach ($arr as $k => $v) {
                // Numeric keys are appended
                if (is_int($k)) {
                    array_key_exists($k, $array) ? array_push($array, $v) : $array[$k] = $v;
                } elseif (is_array($v) and array_key_exists($k, $array) and is_array($array[$k])) {
                    $array[$k] = static::arrMerge($array[$k], $v);
                } else {
                    $array[$k] = $v;
                }
            }
        }

        return $array;
    }

    /**
     * Unsets dot-notated key from an array
     *
     * @param array $array The search array
     * @param mixed $key The dot-notated key or array of keys
     * @return mixed
     */
    public static function arrDelete(&$array, $key)
    {
        if (is_null($key)) {
            return false;
        }

        if (is_array($key)) {
            $return = [];

            foreach ($key as $k) {
                $return[$k] = static::arrDelete($array, $k);
            }

            return $return;
        }

        $key_parts = explode('.', $key);

        if (!is_array($array) or !array_key_exists($key_parts[0], $array)) {
            return false;
        }

        $this_key = array_shift($key_parts);

        if (!empty($key_parts)) {
            $key = implode('.', $key_parts);
            return static::arrDelete($array[$this_key], $key);
        } else {
            unset($array[$this_key]);
        }

        return true;
    }
}

#: END OF ./src/Utils.php FILE
