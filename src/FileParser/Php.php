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

namespace Exen\Konfig\FileParser;

use Exception;
use Exen\Konfig\Exception\ParseException;
use Exen\Konfig\Exception\UnsupportedFileFormatException;

/**
 * Konfig's PHP parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser\AbstractFileParser
 */
class Php extends AbstractFileParser
{
    /**
     * Loads a PHP file and gets its contents as an array.
     *
     * @param string $path File path
     *
     * @throws ParseException             If the PHP file throws an exception
     * @throws UnsupportedFormatException If the PHP file does not return an array
     *
     * @return array The parsed data
     *
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = null;

        // Require the file, if it throws an exception, rethrow it
        try {
            $data = $this->loadFile($path);
        } catch (Exception $ex) {
            throw new ParseException(
                [
                'message' => 'PHP file threw an exception',
                'file' => $path,
                'exception' => $ex,
                ]
            );
        }

        // If we have a callable, run it and expect an array back
        if (is_callable($data)) {
            $data = call_user_func($data);
        }

        // Check for array, if its anything else, throw an exception
        if (empty($data) || !is_array($data)) {
            throw new UnsupportedFileFormatException(
                'PHP file does not return an array'
            );
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @return array Supported extensions
     *
     * @since 0.1.0
     */
    public function getSupportedFileExtensions()
    {
        return ['php', 'inc'];
    }

    /**
     * Loads in the given file and parses it.
     *
     * @param string|bool|null $file File to load
     *
     * @return array The parsed file data
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function loadFile($file = null)
    {
        $this->file = is_file($file) ? $file : false;

        return include $this->file;
    }

    /**
     * Returns the formatted configuration file contents.
     *
     * @param array $contents configuration array
     *
     * @return string formatted configuration file contents
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function exportFormat($contents = null)
    {
        throw new Exception(
            'Saving configuration to `PHP` is not supported at this time'
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
        return 'Exen\Konfig\FileParser\Php' . PHP_EOL;
    }
}

// END OF ./src/FileParser/Php.php FILE
