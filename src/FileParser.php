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

/**
 * Main Konfig's file parser interface
 *
 * @category Main
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */
interface FileParser
{
    /**
     * Parses a file from `$path` and gets its contents as an array.
     *
     * @param string $path Path to parse
     *
     * @return array
     *
     * @since 0.1.0
     */
    public function parse($path);

    /**
     * Returns an array of allowed file extensions for this parser.
     *
     * @return array
     *
     * @since 0.1.0
     */
    public function getSupportedFileExtensions();

    /**
     * Returns configuration group.
     *
     * @return mixed
     *
     * @since 0.2.4
     */
    public function group();
}
