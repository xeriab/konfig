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

class AbstractFileParser implements FileParserInterface
{
    /**
     * Configuration file
     *
     * @var string
     */
    protected $file = null;

    /**
     * The configuration variables
     *
     * @var array
     */
    protected $vars = [];

    /**
     * Sets up the file to be parsed and variables
     *
     * @param string $file Config file name
     * @param array $vars Variables to parse in the file
     * @return void
     */
    public function __construct($file = null, $vars = [])
    {
        $this->file = $file;

        $this->vars = [] + $vars;
    }

    public function load($overwrite = false, $cache = true)
    {
        // Nothing to put here!
    }

    public function parse($file)
    {
        // Nothing to put here!
    }

    public function save($contents)
    {
        // Nothing to put here!
    }

    /**
     * Gets the default group name.
     *
     * @return string
     */
    public function group()
    {
        return $this->file;
    }

    public function getSupportedFileExtensions()
    {
        // Nothing to put here!
    }

    #: Protected Methods

    /**
     * Finds the given config files
     *
     * @param bool $cache
     * param bool $multiple Whether to load multiple files or not
     * @return array
     */
    protected function findFile($cache = true)
    {
        // Nothing to put here!
    }

    /**
     * Parses a string using all of the previously set variables.
     * Allows you to use something like %ENV% in non-PHP files.
     *
     * @param string $string String to parse
     * @return string
     */
    protected function parseVars($string)
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
     */
    protected function prepVars(&$array)
    {
        static $replacements = false;

        if ($replacements === false) {
            foreach ($this->vars as $x => $v) {
                $replacements['#^('.preg_quote($v).'){1}(.*)?#'] = "%".$x."%$2";
            }
        }

        foreach ($array as $x => $value) {
            if (is_string($value)) {
                $array[$x] = preg_replace(
                    array_keys($replacements),
                    array_values($replacements), $
                    value
                );
            } elseif (is_array($value)) {
                $this->prepVars($array[$x]);
            }
        }
    }

    #: Abstract Methods

    /**
     * Must be implemented by child class.
     * Gets called for each file to load.
     */
    abstract protected function loadFile($file);

    /**
     * Must be impletmented by child class.
     * Gets called when saving a configuration file.
     *
     * @param array $contents  config array to save
     * @return string formatted output
     */
    abstract protected function exportFormat($contents);
}

#: END OF ./src/FileParser/AbstractFileParser.php FILE
