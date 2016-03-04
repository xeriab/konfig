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

use Exen\Konfig\Exception\ParseException;
use Piwik\Ini\INIReader as IniReader;

class Ini extends AbstractFileParser
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

        if (empty($data)) {
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

#: END OF ./src/FileParser/Ini.php FILE
