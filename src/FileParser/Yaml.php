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

use Exception;
use Exen\Konfig\Exception\ParseException;
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
        try {
            // Check if the PHP native's YAML extension is exist
            if (!extension_loaded('yaml')) {
                $data = YamlParser::parse(file_get_contents($path));
            } else {
                $data = yaml_parse_file($path);
            }
        } catch (Exception $ex) {
            throw new ParseException([
                'message' => 'Error parsing YAML file',
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
}

#: END OF ./src/FileParser/Yaml.php FILE
