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

use Exen\Konfig\FileParser;

abstract class AbstractFileParser implements FileParser
{
    /**
     * Configuration file
     *
     * @var $file string
     * @since 0.1.0
     */
    protected $file;

    /**
     * The configuration variables
     *
     * @var $vars array
     * @since 0.1.0
     */
    protected $vars = [];

    #: Protected Methods

    /**
     * Parses a string using all of the previously set variables.
     * Allows you to use something like %ENV% in non-PHP files.
     *
     * @param string $string String to parse
     * @return string
     * @since 0.1.0
     */
    protected function parseVars($string = null)
    {
        foreach ($this->vars as $var => $val) {
            $string = str_replace("%$var%", $val, $string);
        }

        return $string;
    }

    /**
     * Replaces given constants to their string counterparts.
     *
     * @param array $array Array to be prepped
     * @return array Prepped array
     * @since 0.1.0
     */
    protected function prepVars(array &$array)
    {
        static $replace = false;

        if ($replace === false) {
            foreach ($this->vars as $x => $v) {
                $replace['#^(' . preg_quote($v) . '){1}(.*)?#'] = '%' . $x . '%$2';
            }
        }

        foreach ($array as $x => $value) {
            if (is_string($value)) {
                $array[$x] = preg_replace(
                        array_keys($replace), array_values($replace), $value
                );
            } elseif (is_array($value)) {
                $this->prepVars($array[$x]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    abstract public function parse($file);

    /**
     * {@inheritdoc}
     */
    abstract public function getSupportedFileExtensions();

    /**
     * @return string
     * @since 0.1.2
     */
    public function __toString()
    {
        return 'Exen\Konfig\FileParser\AbstractFileParser' . PHP_EOL;
    }

}

#: END OF ./src/FileParser/AbstractFileParser.php FILE
