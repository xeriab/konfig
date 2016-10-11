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
use Exen\Konfig\Exception\UnsupportedFileFormatException;

use Exen\Konfig\Utils;

class Php extends AbstractFileParser
{
    /**
     * {@inheritDoc}
     * Loads a PHP file and gets its contents as an array
     *
     * @throws ParseException If the PHP file throws an exception
     * @throws UnsupportedFormatException If the PHP file does not return an array
     * @since 0.1.0
     */
    public function parse($path)
    {
        $data = null;

        // Require the file, if it throws an exception, rethrow it
        try {
            $data = Utils::load(realpath($path));
        } catch (\Exception $ex) {
            throw new ParseException([
                'message' => 'PHP file threw an exception',
                'file' => $path,
                'exception' => $ex,
            ]);
        }

        // If we have a callable, run it and expect an array back
        if (is_callable($data)) {
            $data = call_user_func($data);
        }

        // Check for array, if its anything else, throw an exception
        if (empty($data) || !is_array($data)) {
            throw new UnsupportedFileFormatException('PHP file does not return an array');
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     * @since 0.1.0
     */
    public function getSupportedFileExtensions()
    {
        return ['php', 'inc'];
    }
}

// END OF ./src/FileParser/Php.php FILE
