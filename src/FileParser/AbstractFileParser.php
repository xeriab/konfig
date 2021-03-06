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

use Exen\Konfig\FileParser;

/**
 * Konfig's abstract file parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser
 */
abstract class AbstractFileParser implements FileParser
{
    /**
     * Configuration file.
     *
     * @var string $file
     *
     * @since 0.1.0
     */
    protected $file;

    /**
     * The configuration variables.
     *
     * @var array $variables
     *
     * @since 0.1.0
     */
    protected $variables = [];

    // PROTECTED METHODS

    /**
     * Parses a string using all of the previously set variables.
     * Allows you to use something like %ENV% in non-PHP files.
     *
     * @param string $string String to parse
     *
     * @return             string
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    protected function parseVars($string = null)
    {
        foreach ($this->variables as $var => $value) {
            $string = str_replace("%$var%", $value, $string);
        }

        return $string;
    }

    /**
     * Replaces given constants to their string counterparts.
     *
     * @param array $array Array to be prepped
     *
     * @return             array Prepped array
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    protected function prepVars(array &$array)
    {
        static $replace = false;

        if ($replace === false) {
            foreach ($this->variables as $key => $value) {
                $replace['#^('.
                    preg_quote($value).
                    '){1}(.*)?#'
                ] = '%'.$key.'%$2';
            }
        }

        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $array[$key] = preg_replace(
                    array_keys($replace),
                    array_values($replace),
                    $value
                );
            } elseif (is_array($value)) {
                $this->prepVars($array[$key]);
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $file File path
     *
     * @return array The parsed data
     *
     * @since 0.1.0
     */
    abstract public function parse($file);

    /**
     * {@inheritdoc}
     *
     * @return array Supported extensions
     *
     * @since 0.1.0
     */
    abstract public function getSupportedFileExtensions();

    /**
     * Must be implemented by child class. Gets called for each file to load.
     *
     * @param string $file File path
     *
     * @return array The parsed file data
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    abstract protected function loadFile($file = null);

    /**
     * Must be impletmented by child class. Gets called when saving a config file.
     *
     * @param array $contents config array to save
     *
     * @return string formatted output
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    abstract protected function exportFormat($contents = null);

    /**
     * Gets the default group name.
     *
     * @return string
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public function group()
    {
        return $this->file;
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
        return 'Exen\Konfig\FileParser\AbstractFileParser' . PHP_EOL;
    }
}

// END OF ./src/FileParser/AbstractFileParser.php FILE
