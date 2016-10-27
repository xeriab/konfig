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
     * @return             string The results of the include
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function getContent($file = null)
    {
        return file_get_contents(realpath($file));
    }

    /**
     * Takes a value and checks if it's a Closure or not, if it's a Closure it
     * will return the result of the closure, if not, it will simply return the
     * value.
     *
     * @param string|null $var The value to get
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
     * {@inheritdoc}
     *
     * @param array $array Configuration items
     *
     * @return array
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function unescapeProperties(array &$array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = str_replace('\=', '=', $value);
        }

        // return $array;
    }

    /**
     * Fix value types of the given array.
     *
     * @param array $array The Array to fix
     *
     * @return             array
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function fixArrayValues(array &$array)
    {
        foreach ($array as $key => $value) {
            // Numerical fix
            if (preg_match('/^[1-9][0-9]*$/', $value)) {
                $array[$key] = intval($value);
            }

            // Boolean and semi boolean fix
            if (preg_match('/^true|false|TRUE|FALSE|on|off|ON|OFF*$/', $value)) {
                $array[$key] = boolval($value);
            }

            // Double fix
            if (preg_match('/^[0-9]*[\.]{1}[0-9]*$/', $value)) {
                $array[$key] = doubleval($value);
            }

            // Float fix
            if (preg_match('/^[0-9]*[\.]{1}[0-9-]*$/', $value)) {
                $array[$key] = floatval($value);
            }
        }

        // return $array;
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
     * Trim array elements.
     *
     * @param array $array Configuration items
     *
     * @return             mixed
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function trimArrayElements(array &$array)
    {
        $cb = function ($el) {
            return trim($el);
        };

        $array = array_map($cb, $array);

        // return $content;
    }

    /**
     * Strip Backslashes from given array's elements.
     *
     * @param array $array Array to work with
     *
     * @return             array
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public static function stripBackslashes(array &$array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = str_replace('\=', '=', $value);
        }

        // return $array;
    }

    // Barrowed from: https://github.com/fvsch/php-trim-whitespace

    /**
     * Trim whitespace in multiline text
     *
     * By default, removes leading and trailing whitespace, collapses all other
     * space sequences, and removes blank lines. Most of this can be controlled
     * by passing an options array with the following keys:
     *
     *   - leading (bool, true): should we trim leading whitespace?
     *   - inside (bool, true): should we collapse spaces within a line
     *     (not leading whitespace)?
     *   - blankLines (int, 0): max number of consecutive blank lines;
     *     use false to disable.
     *   - tabWidth (int, 4): number of spaces to use when replacing tabs.
     *
     * The default settings can be used as basic minification for HTML text
     * (except for preformatted text!). This function can  be used remove extra
     * whitespace generated by a mix of PHP and HTML or by a template engine.
     *
     * This function forces two behaviors:
     *   - Trailing whitespace will be removed, always.
     *   - Tab characters will be replaced by space characters, always
     *     (for performance reasons).
     *
     * This was summarily tested on PHP 5.6 and PHP 7 to be as fast as possible,
     * and tested against different approches (e.g. splitting as an array and using
     * PHP’s trim function). The fastest solution found was using str_replace when
     * possible and preg_replace otherwise with very simple regexps to avoid big
     * perf costs. The current implementation should be, if not the fastest
     * possible, probably close enough.
     *
     * @param string $string  String to trim
     * @param array  $options Options
     *
     * @return             string
     * @since              0.2.5
     * @codeCoverageIgnore
     */
    public static function trimWhitespace($string, array $options = [])
    {
        if (!is_string($string)) {
            return '';
        }

        $o = array_merge(
            [
                'leading' => true,
                'inside' => true,
                'blankLines' => 0,
                'tabWidth' => 4,
            ],
            $options
        );

        // Looking for spaces *and* tab characters is way too costly
        // (running times go x4 or x10) so we forcefully replace tab characters
        // with spaces, but make it configurable.
        $tabw = $o['tabWidth'];

        if (!is_int($tabw) || $tabw < 1 || $tabw > 8) {
            $tabw = 4;
        }

        // Replacement patterns should be applied in a specific order
        $patterns = [];

        // Trim leading whitespace first (if active). In typical scenarios,
        // especially for indented HTML, this will remove of the target whitespace
        // and it turns out to be really quick.
        if ($o['leading']) {
            $patterns[] = ['/^ {2,}/m', ''];
        }

        // Always trim at the end. Warning: this seems to be the costlier
        // operation, perhaps because looking ahead is harder?
        $patterns[] = ['/ +$/m', ''];

        // Collapse space sequences inside lines (excluding leading/trailing)
        if ($o['inside']) {
            // No leading spaces? We can avoid a very costly condition!
            // Using a look-behind (or similar solutions) seems to make the whole
            // function go 2x-4x slower (PHP7) or up to 10x slower (PHP 5.6),
            // except on very big strings where whatever perf penalty was incurred
            // seems to be more limited (or at least not exponential).
            $spaces = ($o['leading'] ? ' ' : '(?<=\b) ') . '{2,}';
            $patterns[] = ['/' . $spaces . '/', ' '];
        }

        // Remove empty lines
        if (is_int($l = $o['blankLines']) && $l >= 0) {
            // We need blank lines to be truly empty; if trimStart is disabled
            // we have to fall back to this slightly more costly regex.
            if (!$o['leading']) {
                $patterns[] = ['/^ +$/m', ''];
            }

            // Not using '\R' because it's too slow, so we must do it by hand
            // and replace CRLF before touching any LF.
            $patterns[] = [
                '/(\r\n){' . ($l + 2) . ',}/m',
                str_repeat("\r\n", $l + 1)
            ];

            $patterns[] = [
                '/\n{' . ($l + 2) . ',}/m',
                str_repeat("\n", $l + 1)
            ];
        }

        // Doing the replacement in one go without storing intermediary
        // values helps a bit for big strings (around 20 percent quicker).
        return preg_replace(
            array_map(
                function ($x) {
                    return $x[0];
                },
                $patterns
            ),
            array_map(
                function ($x) {
                    return $x[1];
                },
                $patterns
            ),
            str_replace("\t", str_repeat(' ', $tabw), $string)
        );
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
