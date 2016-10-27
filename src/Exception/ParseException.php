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

namespace Exen\Konfig\Exception;

/**
 * Parse exception class
 *
 * @category Exception.
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */
class ParseException extends ErrorException
{
    /**
     * Constructor.
     *
     * @param array $arr The error array
     *
     * @codeCoverageIgnore
     */
    public function __construct(array $arr)
    {
        $message = isset($arr['message']) ?
            $arr['message'] :
            'There was an error parsing the file';
        $code = isset($arr['code']) ? $arr['code'] : 0;
        $severity = isset($arr['type']) ? $arr['type'] : 1;
        $file = isset($arr['file']) ? $arr['file'] : __FILE__;
        $line = isset($arr['line']) ? $arr['line'] : __LINE__;
        $exception = isset($arr['exception']) ? $arr['exception'] : null;

        parent::__construct(
            $message,
            $code,
            $severity,
            $file,
            $line,
            $exception
        );
    }
}

// END OF ./src/Exception/ParseException.php FILE
