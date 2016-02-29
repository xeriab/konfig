<?php

namespace Exen\Konfig\KonfigFileParser;

use Exen\Konfig\Exception\ParseException;

class Xml implements IKonfigFileParser
{
    /**
     * {@inheritDoc}
     * Loads a XML file as an array
     *
     * @throws ParseException If there is an error parsing XML file
     */
    public function parse($path)
    {
        @libxml_use_internal_errors(true);

        $data = @simplexml_load_file($path, null, LIBXML_NOERROR);

        if ($data === false) {
            $errors = libxml_get_errors();
            $latestError = array_pop($errors);
            $error = array(
                'message' => $latestError->message,
                'type' => $latestError->level,
                'code' => $latestError->code,
                'file' => $latestError->file,
                'line' => $latestError->line,
            );

            throw new ParseException($error);
        }

        $data = @json_decode(@json_encode($data), true);

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

#: END OF ./KonfigFileParser/Xml.php FILE
