<?php

namespace Exen\Konfig\KonfigFileParser;

use Exen\Konfig\Exception\ParseException;
use Piwik\Ini\INIReader as IniReader;

class Ini implements IKonfigFileParser
{
    /**
     * {@inheritDoc}
     * Parses an INI file as an array
     *
     * @throws ParseException If there is an error parsing INI file
     */
    public function parse($path)
    {
        // $data = @parse_ini_file($path, true);
        $iniReader = new IniReader();
        $data = $iniReader->readFile($path);

        if (!$data) {
            $error = error_get_last();
            throw new ParseException($error);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedFileExtensions()
    {
        return ['ini', 'cfg', 'conf'];
    }
}

#: END OF ./KonfigFileParser/Ini.php FILE
