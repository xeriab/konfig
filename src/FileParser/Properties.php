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
use Exen\Konfig\Arr;
use Exen\Konfig\Utils;
use Exen\Konfig\Exception\ParseException;

/**
 * Konfig's Java-Properties parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser\AbstractFileParser
 */
class Properties extends AbstractFileParser
{
    /**
     * Parsed configuration file.
     *
     * @var array $parsedFile
     *
     * @since 0.2.5
     */
    protected $parsedFile;

    /**
     * Loads a PROPERTIES file as an array.
     *
     * @param string $path File path
     *
     * @throws ParseException If there is an error parsing PROPERTIES file
     *
     * @return array The parsed data
     *
     * @since 0.2.4
     */
    public function parse($path)
    {
        $this->loadFile($path);

        $data = $this->parsedFile;

        unset($this->parsedFile);

        if (!is_array($data) || is_null($data) || empty($data)) {
            throw new ParseException(
                [
                    'message' => 'Error parsing PROPERTIES file',
                    'file' => $this->file,
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
        return ['properties'];
    }

    /**
     * Parse Java-Properties
     *
     * @param string|null $string The string to parse
     *
     * @return             array The parsed data
     * @since              0.2.6
     * @codeCoverageIgnore
     */
    private function parseProperties($string = null)
    {
        $result = [];
        $lines = preg_split('/\n\t|\n/', $string);
        $key = '';

        static $isWaitingForOtherLine = false;

        foreach ($lines as $k => $line) {
            if (empty($line) || (!$isWaitingForOtherLine
                && strpos($line, '#') === 0)
            ) {
                continue;
            }

            if (!strpos($line, '=') && !$isWaitingForOtherLine) {
                break;
                return [];
            }

            if (!$isWaitingForOtherLine) {
                $key = substr($line, 0, strpos($line, '='));
                $key = trim($key);
                $value = substr($line, strpos($line, '=') + 1, strlen($line));
            } else {
                $value .= $line;
            }

            // Trim unnecessary white spaces
            $value = trim($value);
            $value = Utils::trimWhitespace($value);

            // Remove unnecessary double/single qoutes
            $value = Utils::removeQuotes($value);

            if (strpos($value, '\\') === strlen($value) - strlen('\\')) {
                $value = substr($value, 0, strlen($value) - 1);
                $isWaitingForOtherLine = true;
            } else {
                $isWaitingForOtherLine = false;
            }

            $result[$key] = empty($value) ? '' : $value;

            unset($lines[$k]);
        }

        Utils::unescapeProperties($result);
        Utils::trimArrayElements($result);
        Utils::stripBackslashes($result);
        Utils::fixArrayValues($result);

        // Fix for dotted properties
        $data = [];

        foreach ($result as $k => $v) {
            Arr::set($data, $k, $v);
        }

        return $data;
    }

    /**
     * Loads in the given file and parses it.
     *
     * @param string|bool|null $file File to load
     *
     * @return array The parsed file data
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function loadFile($file = null)
    {
        $this->file = is_file($file) ? $file : false;

        $contents = $this->parseVars(Utils::getContent($this->file));

        if ($this->file && !is_null($file)) {
            $this->parsedFile = $this->parseProperties($contents);
        }
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
            'Saving configuration to `Properties` is not supported at this time'
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
        return 'Exen\Konfig\FileParser\Properties' . PHP_EOL;
    }
}

// END OF ./src/FileParser/Properties.php FILE
