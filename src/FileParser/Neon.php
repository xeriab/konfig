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

use Nette\Neon\Neon as NeonParser;

class Neon extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a NEON file as an array
     *
     * @throws ParseException If there is an error parsing NEON file
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = null;

        try {
            $data = $this->loadFile($path);
        } catch (\Exception $ex) {
            throw new ParseException([
                'message' => 'Error parsing NEON file',
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
        return ['neon'];
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
        return NeonParser::decode($contents);
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
        $this->prepVars($contents);
        return NeonParser::encode($contents);
    }
}

// END OF ./src/FileParser/Neon.php FILE
