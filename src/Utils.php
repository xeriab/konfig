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

use Closure;

const REQ = 'REQ';
const INC = 'INC';

final class Utils
{
    /**
     * Requires/Includes the given file and returns the results.
     *
     * @param string $file The path to the file
     * @param string $type Type of loading, REQ for require, INC for include
     * @param bool $once Require once or not
     * @return mixed The results of the include
     * @codeCoverageIgnore
     * @since 0.1.0
     */
    public static function load($file = null, $type = REQ, $once = false)
    {
        $path = realpath($file);

        if ($type === REQ || $type === 'REQ') {
            return ($once ? require_once $path : require $path);
        } elseif ($type === INC || $type === 'INC') {
            return ($once ? include_once $path : include $path);
        }

        return;
    }

    /**
     * Get the content of given file and returns the results.
     *
     * @param string $file The path to the file
     * @return mixed The results of the include
     * @codeCoverageIgnore
     * @since 0.2.4
     */
    public static function getContent($file = null)
    {
        return file_get_contents(realpath($file));
    }

    /**
     * Get the given file and returns the results.
     *
     * @param string $path The path to the file
     * @return mixed The results of the include
     * @codeCoverageIgnore
     * @since 0.2.4
     */
    public static function getFile($path = null)
    {
        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * Takes a value and checks if it's a Closure or not, if it's a Closure it
     * will return the result of the closure, if not, it will simply return the
     * value.
     *
     * @param mixed $var The value to get
     * @return mixed
     * @since 0.1.0
     * @codeCoverageIgnore
     */
    public static function checkValue($var = null)
    {
        return ($var instanceof Closure) ? $var() : $var;
    }

    /**
     * Trim array elements
     *
     * @param array $content The array to trim
     * @return mixed
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function trimArrayElements($content = null)
    {
        $cb = function ($el) {
            return trim($el);
        };

        $content = array_map($cb, $content);

        return $content;
    }

    /**
     * Remove quotes
     *
     * @param string $string The string to remove quotes from
     * @return string
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function removeQuotes($string = null)
    {
        if (substr($string, -1) === '"' && substr($string, 0, 1) === '"') {
            $string = substr($string, 1, -1);
        }

        return $string;
    }

    /**
     * @param array|null $content
     * @return array
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function stripBackslashes($content = null)
    {
        foreach ($content as $lineNb => $line) {
            if (substr($line[2], -1) === '\\') {
                $content[$lineNb][2] = trim(substr($line[2], 0, -1));
            }
        }

        return $content;
    }

    /**
     * @param string $needle
     * @param string $string
     * @return bool
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function stringStart($needle, $string)
    {
        return (substr($string, 0, 1) === $needle) ? true : false;
    }

    /**
     * @param string $path The path to the file
     * @return array
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function fileToArray($path = null)
    {
        // $result = self::getFile($path);
        $result = self::getContent($path);

        $lines = explode(PHP_EOL, $result);

        print_r($lines);

        $result = self::trimArrayElements($result);
        $result = array_filter($result);

        return $result;
    }

    /**
     * @param string $content The file content
     * @return string
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function fileContentToArray($content = null)
    {
        $result = [];

        $lines = explode(PHP_EOL, $content);

        foreach ($lines as $key) {
            if ($key !== "" || !is_null($key) || !empty($key)) {
                $result[] = $key;
            }
        }

        $result = self::trimArrayElements($result);
        $result = array_filter($result);

        return $result;
    }

    /**
     * @param string $type The line type
     * @param array $analysis Array to analyze
     * @return int
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function getNumberLinesMatching($type, array $analysis)
    {
        $counter = 0;

        foreach ($analysis as $value) {
            if ($value[0] === $type) {
                $counter++;
            }
        }

        return $counter;
    }

    /**
     * @param $callback
     * @param array $args
     * @return void
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    public static function callFuncArray($callback, array $args)
    {
        // deal with "class::method" syntax
        if (is_string($callback) && strpos($callback, '::') !== false) {
            $callback = explode('::', $callback);
        }

        // if an array is passed, extract the object and method to call
        if (is_array($callback) && isset($callback[1]) && is_object($callback[0])) {
            list($instance, $method) = $callback;

            // Calling the method directly is faster then call_user_func_array() !
            switch (count($args)) {
                case 0:
                    return $instance->$method();

                case 1:
                    return $instance->$method($args[0]);

                case 2:
                    return $instance->$method($args[0], $args[1]);

                case 3:
                    return $instance->$method($args[0], $args[1], $args[2]);

                case 4:
                    return $instance->$method($args[0], $args[1], $args[2], $args[3]);
            }
        } elseif (is_array($callback) && isset($callback[1]) && is_string($callback[0])) {
            list($class, $method) = $callback;
            $class = '\\'.ltrim($class, '\\');

            // Calling the method directly is faster then call_user_func_array() !
            switch (count($args)) {
                case 0:
                    return $class::$method();

                case 1:
                    return $class::$method($args[0]);

                case 2:
                    return $class::$method($args[0], $args[1]);

                case 3:
                    return $class::$method($args[0], $args[1], $args[2]);

                case 4:
                    return $class::$method($args[0], $args[1], $args[2], $args[3]);
            }
            // if it's a string, it's a native function or a static method call
        } elseif (is_string($callback) || $callback instanceof Closure) {
            is_string($callback) && $callback = ltrim($callback, '\\');

            // calling the method directly is faster then call_user_func_array() !
            switch (count($args)) {
                case 0:
                    return $callback();

                case 1:
                    return $callback($args[0]);

                case 2:
                    return $callback($args[0], $args[1]);

                case 3:
                    return $callback($args[0], $args[1], $args[2]);

                case 4:
                    return $callback($args[0], $args[1], $args[2], $args[3]);
            }
        }

        // fallback, handle the old way
        return call_user_func_array($callback, $args);
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
