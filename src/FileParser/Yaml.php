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

use Exception;
use Exen\Konfig\Exception\ParseException;

class Yaml extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a YAML/YML file as an array
     *
     * @throws ParseException If there is an error parsing YAML/YML file
     */
    public function parse($path)
    {
        try
        {
            // Check for native YAML PHP extension
            $nYaml = extension_loaded('yaml');
            $data = null;

            if (!$nYaml) {
                $data = spyc_load_file($path);
            } else {
                $data = yaml_parse_file($path);
            }
        } catch (Exception $ex) {
            throw new ParseException(
                [
                    'message' => 'Error parsing YAML file',
                    'exception' => $ex,
                ]
            );
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
