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

#: END OF ./src/FileParser/FileParserInterface.php FILE
