<?php

namespace Exen\Konfig\FileParser;

interface FileParserInterface
{
    /**
     * Parses a file from `$path` and gets its contents as an array
     *
     * @param string $path Path to parse
     *
     * @return array
     */
    public function parse($path);

    /**
     * Returns an array of allowed file extensions for this parser
     *
     * @return array
     */
    public function getSupportedFileExtensions();
}

#: END OF ./FileParser/FileParserInterface.php FILE
