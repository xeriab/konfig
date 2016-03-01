<?php

namespace Exen\Konfig\FileParser;

use Exen\Konfig\Exception\ParseException;

class Json implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a JSON file as an array
     *
     * @throws ParseException If there is an error parsing JSON file
     */
    public function parse($path)
    {
        $data = @json_decode(@file_get_contents($path), true);

        if (function_exists('json_last_error_msg')) {
            $error_message = json_last_error_msg();
        } else {
            $error_message = 'Syntax error';
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = [
                'message' => $error_message,
                'type' => json_last_error(),
                'file' => $path,
            ];

            throw new ParseException($error);
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
}

#: END OF ./FileParser/Json.php FILE
