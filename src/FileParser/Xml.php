<?php
/**
 * Konfig
 *
 * Yet another simple configuration file loader library.
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
     * @since 0.1
     */
    public function parse($path)
    {
        libxml_use_internal_errors(false);

        $data = simplexml_load_file($path, null, LIBXML_NOERROR);

        if ($data === false) {
            $errors = libxml_get_errors();
            $latestError = array_pop($errors);
            $error = [
                'message' => $latestError->message,
                'type' => $latestError->level,
                'code' => $latestError->code,
                'file' => $latestError->file,
                'line' => $latestError->line,
            ];

            throw new ParseException($error);
        }

        $data = json_decode(@json_encode($data), true);

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedFileExtensions()
    {
        return ['xml'];
    }
}

#: END OF ./src/FileParser/Xml.php FILE
