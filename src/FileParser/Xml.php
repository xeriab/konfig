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

class Xml extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a XML file as an array
     *
     * @throws ParseException If there is an error parsing XML file
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = $this->loadFile($path);

        if ($data === false) {
            $lastError = libxml_get_last_error();

            if ($lastError !== false) {
                throw new ParseException([
                    'message' => $lastError->message,
                    'type' => $lastError->level,
                    'code' => $lastError->code,
                    'file' => $lastError->file,
                    'line' => $lastError->line,
                ]);
            }
        }

        return json_decode(json_encode($data), true);
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedFileExtensions()
    {
        return ['xml'];
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
        return simplexml_load_string(
            $contents,
            'SimpleXMLElement',
            LIBXML_NOWARNING | LIBXML_NOERROR
        );
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
        throw new \Exception('Saving configuration to `XML` is not supported at this time');
    }
}

// END OF ./src/FileParser/Xml.php FILE
