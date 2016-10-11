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

use Yosymfony\Toml\Toml as TomlLib;

class Toml extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a TOML file as an array
     *
     * @throws ParseException If there is an error parsing TOML file
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = null;

        try {
            $data = TomlLib::Parse(realpath($path));
        } catch (\Exception $ex) {
            throw new ParseException([
                'message' => 'Error parsing TOML file',
                'file' => $path,
                'exception' => $ex,
            ]);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedFileExtensions()
    {
        return ['toml'];
    }
}

// END OF ./src/FileParser/Toml.php FILE
