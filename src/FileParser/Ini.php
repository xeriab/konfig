<?php

/**
 * Konfig.
 *
 * Yet another simple configuration loader library.
 *
 * PHP version 5
 *
 * @category Library
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */

namespace Exen\Konfig\FileParser;

use Exception;
use Exen\Konfig\Exception\ParseException;
use Exen\Konfig\Utils;

/**
 * Konfig's INI parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser\AbstractFileParser
 */
class Ini extends AbstractFileParser
{
    /**
     * Parses an INI file as an array.
     *
     * @param string $path File path
     *
     * @throws ParseException If there is an error parsing INI file
     *
     * @return array The parsed data
     *
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
     * {@inheritdoc}
     *
     * @return array Supported extensions
     *
     * @since 0.1.0
     */
    public function getSupportedFileExtensions()
    {
        return ['ini', 'cfg'];
    }

    /**
     * Loads in the given file and parses it.
     *
     * @param string $file File to load
     *
     * @return array The parsed file data
     *
     * @since              0.2.4
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
     * @param array $contents configuration array
     *
     * @return string formatted configuration file contents
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function exportFormat($contents = null)
    {
        throw new Exception(
            'Saving configuration to `INI` is not supported at this time'
        );
    }

    /**
     * __toString.
     *
     * @return             string
     * @since              0.1.2
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return 'Exen\Konfig\FileParser\Ini' . PHP_EOL;
    }
}

// END OF ./src/FileParser/Ini.php FILE
