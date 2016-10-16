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

use Exen\Konfig\Utils;
use Exen\Konfig\Exception\Exception;
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
            $data = $this->loadFile($path);
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

    /**
     * Loads in the given file and parses it.
     *
     * @param   string  $file File to load
     * @return  array
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    protected function loadFile($file = null)
    {
        $this->file = $file;
        $contents = $this->parseVars(Utils::getContent($file));
        return TomlLib::Parse($contents);
    }

    /**
     * Returns the formatted configuration file contents.
     *
     * @param   array   $contents  configuration array
     * @return  string  formatted configuration file contents
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    protected function exportFormat($contents = null)
    {
        throw new \Exception('Saving configuration to `TOML` is not supported at this time');
    }
}

// END OF ./src/FileParser/Toml.php FILE
