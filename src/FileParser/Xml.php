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
        $data = simplexml_load_file(
            realpath($path),
            'SimpleXMLElement',
            LIBXML_NOWARNING | LIBXML_NOERROR
        );

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
}

// END OF ./src/FileParser/Xml.php FILE
