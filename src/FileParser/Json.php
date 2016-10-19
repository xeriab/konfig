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

use Exen\Konfig\Exception\ParseException;
use Exen\Konfig\Utils;

/**
 * Json
 * Konfig's JSON parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser\AbstractFileParser
 */
class Json extends AbstractFileParser
{
    /**
     * Loads a JSON file as an array.
     *
     * @param string $path File path
     *
     * @throws ParseException If there is an error parsing JSON file
     *
     * @return array The parsed data
     *
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = $this->loadFile($path);

        if (function_exists('json_last_error_msg')) {
            $error_message = json_last_error_msg();
        } else {
            $error_message = 'Syntax error';
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ParseException(
                [
                'message' => $error_message,
                'type' => json_last_error(),
                'file' => $path,
                ]
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
        return ['json'];
    }

    /**
     * Loads in the given file and parses it.
     *
     * @param string $file File to load
     *
     * @return array The parsed file data
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function loadFile($file = null)
    {
        $this->file = $file;
        $contents = $this->parseVars(Utils::getContent($file));

        return json_decode($contents, true);
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
        $this->prepVars($contents);

        return json_encode($contents);
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
        return 'Exen\Konfig\FileParser\Json' . PHP_EOL;
    }
}

// END OF ./src/FileParser/Json.php FILE
