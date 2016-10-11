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

use Exen\Konfig\Exception\ParseException;

use Nette\Neon\Neon as NeonParser;

class Neon extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a NEON file as an array
     *
     * @throws ParseException If there is an error parsing NEON file
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = null;

        try {
            $data = NeonParser::decode(file_get_contents(realpath($path)));
        } catch (\Exception $ex) {
            throw new ParseException([
                'message' => 'Error parsing NEON file',
                'file' => $path,
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
        return ['neon'];
    }
}

// END OF ./src/FileParser/Neon.php FILE
