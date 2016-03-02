<?php

namespace Exen\Konfig\FileParser;

use Exception;
use Exen\Konfig\Exception\ParseException;
use Nette\Neon\Neon;

class Neon implements FileParserInterface
{
    /**
     * {@inheritDoc}
     * Loads a NEON file as an array
     *
     * @throws ParseException If there is an error parsing NEON file
     */
    public function parse($path)
    {
        try {
            $content = @file_get_contents($path);
            $data = Neon::decode($content);
        } catch (Exception $ex) {
            throw new ParseException(
                array(
                    'message' => 'Error parsing NEON file',
                    'exception' => $ex,
                )
            );
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedFileExtensions()
    {
        return ['neon'];
    }
}

#: END OF ./FileParser/Neon.php FILE
