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

namespace Exen\Konfig\FileParser;

use Exen\Konfig\Utils;
use Exen\Konfig\Exception\Exception;
use Exen\Konfig\Exception\ParseException;

class Json extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a JSON file as an array
     *
     * @throws ParseException If there is an error parsing JSON file
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
            throw new ParseException([
                'message' => $error_message,
                'type' => json_last_error(),
                'file' => $path,
            ]);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedFileExtensions()
    {
        return ['json'];
    }

    /**
     * Loads in the given file and parses it.
     *
     * @param   string  $file File to load
     * @return  array
     * @since 0.2.4
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
     * @param   array   $contents  configuration array
     * @return  string  formatted configuration file contents
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    protected function exportFormat($contents = null)
    {
        $this->prepVars($contents);
        return json_encode($contents);
    }
}

// END OF ./src/FileParser/Json.php FILE
