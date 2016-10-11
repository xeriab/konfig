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

use Exen\Konfig\Exception\Exception;
use Exen\Konfig\Exception\ParseException;

class Ini extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Parses an INI file as an array
     *
     * @throws ParseException If there is an error parsing INI file
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = @parse_ini_file(realpath($path));

        if (!$data) {
            throw new ParseException(error_get_last());
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     * @since 0.1.0
     */
    public function getSupportedFileExtensions()
    {
        return ['ini', 'cfg'];
    }
}

// END OF ./src/FileParser/Ini.php FILE
