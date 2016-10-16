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

use ArrayAccess;
use InvalidArgumentException;

use Exen\Konfig\Utils;

final class Arr
{
    /**
     * Gets a dot-notated key from an array, with a default value if it does
     * not exist.
     *
     * @param array $array The search array
     * @param string $key The dot-notated key or array of keys
     * @param string $default The default value
     * @return mixed
     * @codeCoverageIgnore
     * @since 0.1.0
     */
    public static function get(array $array, $key, $default = null)
    {
        if (!is_array($array) && !$array instanceof ArrayAccess) {
            throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
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
            if (($array instanceof ArrayAccess && isset($array[$key_part])) === false) {
                if (!is_array($array) || !array_key_exists($key_part, $array)) {
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
     * @param mixed $key The dot-notated key to set or array of keys
     * @param mixed $value The value
     * @return void
     * @codeCoverageIgnore
     * @since 0.1.0
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

                if (!isset($array[$key]) || ! is_array($array[$key])) {
                    $array[$key] = [];
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
     * @throws InvalidArgumentException
     * @return array
     * @codeCoverageIgnore
     * @since 0.1.0
     */
    public static function merge()
    {
        $array = func_get_arg(0);
        $arrays = array_slice(func_get_args(), 1);

        if (!is_array($array)) {
            throw new InvalidArgumentException('Exen\Konfig\Arr::merge() - all arguments must be arrays.');
        }

        foreach ($arrays as $arr) {
            if (!is_array($arr)) {
                throw new InvalidArgumentException('Exen\Konfig\Arr::merge() - all arguments must be arrays.');
            }

            foreach ($arr as $key => $value) {
                // Numeric keys are appended
                if (is_int($key)) {
                    array_key_exists($key, $array) ? array_push($array, $value) : $array[$key] = $value;
                } elseif (is_array($value) && array_key_exists($key, $array) && is_array($array[$key])) {
                    $array[$key] = self::merge($array[$key], $value);
                } else {
                    $array[$key] = $value;
                }
            }
        }

        return $array;
    }
    
    /**
     * Merge 2 arrays recursively, differs in 2 important ways from array_merge_recursive()
     * - When there's 2 different values and not both arrays, the latter value overwrites the earlier
     *   instead of merging both into an array
     * - Numeric keys are never changed
     *
     * @param array multiple variables all of which must be arrays
     * @throws InvalidArgumentException
     * @return array
     * @codeCoverageIgnore
     * @since 0.1.0
     */
    public static function mergeAssoc()
    {
        $array = func_get_arg(0);
        $arrays = array_slice(func_get_args(), 1);

        if (!is_array($array)) {
            throw new InvalidArgumentException('Exen\Konfig\Arr::mergeAssoc() - all arguments must be arrays.');
        }

        foreach ($arrays as $arr) {
            if (!is_array($arr)) {
                throw new InvalidArgumentException('Exen\Konfig\Arr::mergeAssoc() - all arguments must be arrays.');
            }

            foreach ($arr as $key => $value) {
                if (is_array($value) && array_key_exists($key, $array) && is_array($array[$key])) {
                    $array[$key] = self::mergeAssoc($array[$key], $value);
                } else {
                    $array[$key] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * Un-sets dot-notated key from an array
     *
     * @param array $array The search array
     * @param mixed $key The dot-notated key or array of keys
     * @return mixed
     * @codeCoverageIgnore
     * @since 0.1.0
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

        if (!is_array($array) || ! array_key_exists($key_parts[0], $array)) {
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
     * Get array keys recursively
     *
     * @param array $array The search array
     * @param int $maxDepth The search maximum depth
     * @param int $depth The search depth
     * @param array $arraykeys The array keys
     * @return array
     * @codeCoverageIgnore
     * @since 0.1.0
     */
    public static function keys(array $array, $maxDepth = INF, $depth = 0, array $arraykeys = [])
    {
        if ($depth < $maxDepth) {
            $depth++;
            $keys = array_keys($array);

            foreach ($keys as $key) {
                if (is_array($array[$key])) {
                    $arraykeys[$key] = self::keys($array[$key], $maxDepth, $depth);
                }
            }
        }

        return $arraykeys;
    }
    
    /**
     * Get array keys recursively
     *
     * @param array $array The search array
     * @param string $search The search value
     * @return array
     * @codeCoverageIgnore
     * @since 0.1.2
     */
    public static function recursiveKeys(array $array, $search = null)
    {
        $return = (
            $search !== null ?
            array_keys($array, $search) :
            array_keys($array)
        );
        
        foreach ($array as $sub) {
            if (is_array($sub)) {
                $return = (
                    $search !== null ?
                    self::merge($return, self::recursiveKeys($sub, $search)) :
                    self::merge($return, self::recursiveKeys($sub))
                );
            }
        }
        
        return $return;
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param  array   $array  collection of arrays to pluck from
     * @param  string  $key    key of the value to pluck
     * @param  string  $index  optional return array index key, true for original index
     * @return array   array of plucked values
     * @codeCoverageIgnore
     * @since 0.2.4
     */
    public static function pluck($array, $key, $index = null)
    {
        $return = [];
        $get_deep = strpos($key, '.') !== false;

        if (!$index) {
            foreach ($array as $i => $a) {
                $return[] = (is_object($a) && ! ($a instanceof ArrayAccess)) ? $a->{$key} :
                    ($get_deep ? self::get($a, $key) : $a[$key]);
            }
        } else {
            foreach ($array as $i => $a) {
                $index !== true && $i = (is_object($a) && ! ($a instanceof ArrayAccess)) ? $a->{$index} : $a[$index];
                $return[$i] = (is_object($a) && ! ($a instanceof ArrayAccess)) ? $a->{$key} :
                    ($get_deep ? self::get($a, $key) : $a[$key]);
            }
        }

        return $return;
    }

    /**
     * Sorts a multi-dimensional array by it's values.
     *
     * @access  public
     * @param   array   The array to fetch from
     * @param   string  The key to sort by
     * @param   string  The order (asc or desc)
     * @param   int     The php sort type flag
     * @return  array
     * @codeCoverageIgnore
     * @since 0.2.4
     */
    public static function sort(array $array, $key, $order = 'asc', $sort_flags = SORT_REGULAR)
    {
        if (!is_array($array)) {
            throw new InvalidArgumentException('Exen\Konfig\Arr::sort() - $array must be an array.');
        }

        if (empty($array)) {
            return $array;
        }

        $b = [];
        $c = [];

        foreach ($array as $k => $v) {
            $b[$k] = self::get($v, $key);
        }

        switch ($order) {
            case 'asc':
                asort($b, $sort_flags);
                break;

            case 'desc':
                arsort($b, $sort_flags);
                break;

            default:
                throw new InvalidArgumentException('Exen\Konfig\Arr::sort() - $order must be asc or desc.');
                break;
        }

        foreach ($b as $key => $val) {
            $c[] = $array[$key];
        }

        return $c;
    }

    /**
     * Sorts an array on multitiple values, with deep sorting support.
     *
     * @param   array  $array        collection of arrays/objects to sort
     * @param   array  $conditions   sorting conditions
     * @param   bool   @ignore_case  wether to sort case insensitive
     */
    public static function multiSort(array $array, $conditions, $ignore_case = false)
    {
        $temp = [];
        $keys = array_keys($conditions);

        foreach ($keys as $key) {
            $temp[$key] = self::pluck($array, $key, true);
            is_array($conditions[$key]) || $conditions[$key] = array($conditions[$key]);
        }

        $args = [];

        foreach ($keys as $key) {
            $args[] = $ignore_case ? array_map('strtolower', $temp[$key]) : $temp[$key];
            foreach ($conditions[$key] as $flag) {
                $args[] = $flag;
            }
        }

        $args[] = &$array;

        Utils::callFuncArray('array_multisort', $args);

        return $array;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     * @since 0.1.2
     */
    public function __toString()
    {
        return 'Exen\Konfig\Arr' . PHP_EOL;
    }
}

// END OF ./src/Arr.php FILE
