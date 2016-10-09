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
use Nette\Neon\Neon as NeonLib;

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
        try {
            $content = file_get_contents($path);
            $data = NeonLib::decode($content);
        } catch (Exception $ex) {
            throw new ParseException([
                'message' => 'Error parsing NEON file',
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

#: END OF ./src/FileParser/Neon.php FILE
