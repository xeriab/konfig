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

use Closure;

/**
 * Utils.
 *
 * Konfig's utilities class
 *
 * @category Main
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */
final class Utils
{
    /**
     * Get the content of given file and returns the results.
     *
     * @param string $file The path to the file
     *
     * @return             mixed The results of the include
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function getContent($file = null)
    {
        return file_get_contents(realpath($file));
    }

    /**
     * Get the given file and returns the results.
     *
     * @param string $path The path to the file
     *
     * @return             mixed The results of the include
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function getFile($path = null)
    {
        return file(
            realpath($path),
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );
    }

    /**
     * Takes a value and checks if it's a Closure or not, if it's a Closure it
     * will return the result of the closure, if not, it will simply return the
     * value.
     *
     * @param mixed $var The value to get
     *
     * @return             mixed
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public static function checkValue($var = null)
    {
        return ($var instanceof Closure) ? $var() : $var;
    }

    /**
     * Trim array elements.
     *
     * @param array $content The array to trim
     *
     * @return             mixed
     * @since              0.2.4
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
     * Remove quotes.
     *
     * @param string $string The string to remove quotes from
     *
     * @return             string
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function removeQuotes($string = null)
    {
        if (substr($string, -1) === '"' && substr($string, 0, 1) === '"') {
            $string = substr($string, 1, -1);
        } elseif (substr($string, -1) === '\'' && substr($string, 0, 1) === '\'') {
            $string = substr($string, 1, -1);
        }

        return $string;
    }

    /**
     * Strip Backslashes from given string.
     *
     * @param array $content String
     *
     * @return             array
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function stripBackslashes($content)
    {
        foreach ($content as $lineNb => $line) {
            if (substr($line[2], -1) === '\\') {
                $content[$lineNb][2] = trim(substr($line[2], 0, -1));
            }
        }

        return $content;
    }

    /**
     * Checks if the string starts with the given needle.
     *
     * @param string $needle Search string
     * @param string $string String to search in
     *
     * @return             bool
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function stringStart($needle, $string)
    {
        return (substr($string, 0, 1) === $needle) ? true : false;
    }

    /**
     * Opens given file and convert it to an array.
     *
     * @param string $path The path to the file
     *
     * @return             array
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function fileToArray($path = null)
    {
        $result = self::getFile($path);
        // $result = self::getContent($path);

        // $lines = explode(PHP_EOL, $result);
        // $lines = explode("\n\t|\n", $result);

        $result = self::trimArrayElements($result);
        $result = array_filter($result);

        return $result;
    }

    /**
     * Opens given file and convert it to an array.
     *
     * @param string|null $content The file content
     *
     * @return             array
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function fileContentToArray($content = null)
    {
        // $result = explode(PHP_EOL, $content);
        $result = preg_split('/\n\t|\n/', $content);
        $result = self::trimArrayElements($result);
        // $result = array_filter($result);

        return $result;
    }

    /**
     * Get lines matching number.
     *
     * @param string $type     The line type
     * @param array  $analysis Array to analyze
     *
     * @return             int
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function getNumberLinesMatching($type, array $analysis)
    {
        $counter = 0;

        foreach ($analysis as $value) {
            if ($value[0] === $type) {
                ++$counter;
            }
        }

        return $counter;
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
        return 'Exen\Konfig\Utils' . PHP_EOL;
    }
}

// END OF ./src/Utils.php FILE
