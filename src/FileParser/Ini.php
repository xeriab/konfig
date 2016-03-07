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

class Ini extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Parses an INI file as an array
     *
     * @throws ParseException If there is an error parsing INI file
     * @since 0.1
     */
    public function parse($path)
    {
        $data = @parse_ini_file($path, true);

        if (empty($data) || !$data) {
            $error = error_get_last();
            throw new ParseException($error);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     * @since 0.1
     */
    public function getSupportedFileExtensions()
    {
        return ['ini'];
    }
}

#: END OF ./src/FileParser/Ini.php FILE
