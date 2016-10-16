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
use Exen\Konfig\Utils;

use Symfony\Component\Yaml\Yaml as YamlParser;

class Yaml extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a YAML/YML file as an array
     *
     * @throws ParseException If there is an error parsing YAML/YML file
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = null;

        try {
            $data = $this->loadFile($path);
        } catch (\Exception $ex) {
            throw new ParseException([
                'message' => 'Error parsing YAML file',
                'file' => realpath($path),
                'line' => $ex->getParsedLine(),
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
        return ['yaml', 'yml'];
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
        return YamlParser::parse($contents);
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
        return YamlParser::dump($contents);
    }
}

// END OF ./src/FileParser/Yaml.php FILE
