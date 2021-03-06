<?php

/**
 * Konfig.
 *
 * Yet another simple configuration loader library.
 *
 * PHP version 5
 *
 * @category Library
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */

namespace Exen\Konfig;

use ArrayAccess;
use InvalidArgumentException;

/**
 * Konfig's array utilities class
 *
 * @category Main
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */
final class Arr
{
    /**
     * Gets a dot-notated key from an array, with a default value if it does
     * not exist.
     *
     * @param array  $array   The search array
     * @param string $key     The dot-notated key or array of keys
     * @param string $default The default value
     *
     * @return mixed
     *
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public static function get(array $array, $key, $default = null)
    {
        if (!is_array($array) && !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException(
                'First parameter must be an array or ArrayAccess object.'
            );
        }

        if (is_null($key)) {
            return $array;
        }

        if (is_array($key)) {
            $return = [];

            foreach ($key as $k) {
                $return[$k] = self::get($array, $k, $default);
            }

            return $return;
        }

        foreach (explode('.', $key) as $key_part) {
            if (($array instanceof ArrayAccess
                && isset($array[$key_part])) === false
            ) {
                if (!is_array($array)
                    || !array_key_exists($key_part, $array)
                ) {
                    return Utils::checkValue($default);
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
     * @param mixed $key   The dot-notated key to set or array of keys
     * @param mixed $value The value
     *
     * @return             void Void
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public static function set(array &$array, $key, $value = null)
    {
        if (is_null($key)) {
            $array = $value;

            return;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::set($array, $k, $v);
            }
        } else {
            $keys = explode('.', $key);

            while (count($keys) > 1) {
                $key = array_shift($keys);

                if (!isset($array[$key])
                    || !is_array($array[$key])
                ) {
                    $array[$key] = [];
                }

                $array = &$array[$key];
            }

            $array[array_shift($keys)] = $value;
        }
    }

    /**
     * Merge two arrays recursively.
     *
     * @throws InvalidArgumentException
     *
     * @return array
     *
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public static function merge()
    {
        $array = func_get_arg(0);
        $arrays = array_slice(func_get_args(), 1);

        if (!is_array($array)) {
            throw new InvalidArgumentException(
                'Exen\Konfig\Arr::merge() - all arguments must be arrays.'
            );
        }

        foreach ($arrays as $arr) {
            if (!is_array($arr)) {
                throw new InvalidArgumentException(
                    'Exen\Konfig\Arr::merge() - all arguments must be arrays.'
                );
            }

            foreach ($arr as $key => $value) {
                // Numeric keys are appended
                if (is_int($key)) {
                    array_key_exists($key, $array) ?
                        array_push($array, $value) :
                        $array[$key] = $value;
                } elseif (is_array($value)
                    && array_key_exists($key, $array)
                    && is_array($array[$key])
                ) {
                    $array[$key] = self::merge($array[$key], $value);
                } else {
                    $array[$key] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * Merge two arrays recursively | assoc.
     *
     * @throws InvalidArgumentException
     *
     * @return array
     *
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public static function mergeAssoc()
    {
        $array = func_get_arg(0);
        $arrays = array_slice(func_get_args(), 1);

        if (!is_array($array)) {
            throw new InvalidArgumentException(
                'Exen\Konfig\Arr::mergeAssoc() - all arguments must be arrays.'
            );
        }

        foreach ($arrays as $arr) {
            if (!is_array($arr)) {
                throw new InvalidArgumentException(
                    'Exen\Konfig\Arr::mergeAssoc() - all arguments must be arrays.'
                );
            }

            foreach ($arr as $key => $value) {
                if (is_array($value)
                    && array_key_exists($key, $array)
                    && is_array($array[$key])
                ) {
                    $array[$key] = self::mergeAssoc($array[$key], $value);
                } else {
                    $array[$key] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * Un-sets dot-notated key from an array.
     *
     * @param array $array The search array
     * @param mixed $key   The dot-notated key or array of keys
     *
     * @return mixed
     *
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public static function delete(array &$array, $key)
    {
        if (is_null($key)) {
            return false;
        }

        if (is_array($key)) {
            $return = [];

            foreach ($key as $k) {
                $return[$k] = self::delete($array, $k);
            }

            return $return;
        }

        $key_parts = explode('.', $key);

        if (!is_array($array)
            || !array_key_exists($key_parts[0], $array)
        ) {
            return false;
        }

        $this_key = array_shift($key_parts);

        if (!empty($key_parts)) {
            $key = implode('.', $key_parts);
            return self::delete($array[$this_key], $key);
        } else {
            unset($array[$this_key]);
        }

        return true;
    }

    /**
     * __toString.
     *
     * @return             string
     * @since              0.1.2
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return 'Exen\Konfig\Arr' . PHP_EOL;
    }
}

// END OF ./src/Arr.php FILE
