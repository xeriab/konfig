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
use Symfony\Component\Yaml\Yaml as YamlParser;

/**
 * Konfig's YAML parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser\AbstractFileParser
 */
class Yaml extends AbstractFileParser
{
    /**
     * Loads a YAML/YML file as an array.
     *
     * @param string $path File path
     *
     * @throws ParseException If there is an error parsing YAML/YML file
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
                    'message' => 'Error parsing YAML file',
                    'file' => realpath($path),
                    'line' => $ex->getParsedLine(),
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
        return ['yaml', 'yml'];
    }

    /**
     * Loads in the given file and parses it.
     *
     * @param string $file File to load
     *
     * @return string|array|stdClass The parsed file data
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function loadFile($file = null)
    {
        $this->file = $file;
        $contents = $this->parseVars(Utils::getContent($file));

        if (extension_loaded('yaml')) {
            return yaml_parse($content);
        }

        return YamlParser::parse($contents);
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

        if (extension_loaded('yaml')) {
            return yaml_emit($content);
        }

        return YamlParser::dump($contents);
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
        return 'Exen\Konfig\FileParser\Yaml' . PHP_EOL;
    }
}

// END OF ./src/FileParser/Yaml.php FILE
