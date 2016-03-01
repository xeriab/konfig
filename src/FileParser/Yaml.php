<?php

namespace Exen\Konfig\FileParser;

use Exen\Konfig\Exception\ParseException;

class Yaml implements FileParserInterface
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
            $nativYaml = (function_exists('yaml_parse_file'));
            $content = @file_get_contents($path);
            $data = null;

            if (!$nativYaml) {
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

#: END OF ./FileParser/Yaml.php FILE
