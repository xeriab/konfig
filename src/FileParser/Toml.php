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

use Exception;
use Exen\Konfig\Exception\ParseException;
use Yosymfony\Toml\Toml as TomlLib;

class Toml extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a TOML file as an array
     *
     * @throws ParseException If there is an error parsing TOML file
     */
    public function parse($path)
    {
        try {
            $data = TomlLib::Parse($path);
        } catch (Exception $ex) {
            throw new ParseException(
                array(
                    'message' => 'Error parsing TOML file',
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
        return ['toml'];
    }
}

#: END OF ./src/FileParser/Toml.php FILE
