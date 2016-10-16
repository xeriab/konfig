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
        $data = $this->loadFile($path);

        if (!$data || empty($data) || !is_array($data)) {
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
        return @parse_ini_string($contents, true);
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
        throw new \Exception('Saving configuration to `INI` is not supported at this time');
    }

    /**
     * @return string
     * @since 0.1.2
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return 'Exen\Konfig\FileParser\Ini' . PHP_EOL;
    }
}

// END OF ./src/FileParser/Ini.php FILE
