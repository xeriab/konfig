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
use Nette\Neon\Neon as NeonParser;

/**
 * Konfig's NEON parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser\AbstractFileParser
 */
class Neon extends AbstractFileParser
{
    /**
     * Loads a NEON file as an array.
     *
     * @param string $path File path
     *
     * @throws ParseException If there is an error parsing NEON file
     *
     * @return array The parsed data
     *
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = null;

        try {
            $data = $this->loadFile($path);
        } catch (Exception $ex) {
            throw new ParseException(
                [
                'message' => 'Error parsing NEON file',
                'file' => $path,
                'exception' => $ex,
                ]
            );
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
        return ['neon'];
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

        return NeonParser::decode($contents);
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
        $this->prepVars($contents);
        return NeonParser::encode($contents);
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
        return 'Exen\Konfig\FileParser\Neon' . PHP_EOL;
    }
}

// END OF ./src/FileParser/Neon.php FILE
