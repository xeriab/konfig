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

namespace Exen\Konfig\Exception;

use Exen\Konfig\ErrorException;

class ParseException extends ErrorException
{
    public function __construct(array $error)
    {
        $message   = $error['message'] ?: 'There was an error parsing the file';
        $code      = isset($error['code']) ? $error['code'] : 0;
        $severity  = isset($error['type']) ? $error['type'] : 1;
        $fileName  = isset($error['file']) ? $error['file'] : __FILE__;
        $lineNo    = isset($error['line']) ? $error['line'] : __LINE__;
        $exception = isset($error['exception']) ? $error['exception'] : null;

        parent::__construct($message, $code, $severity, $fileName, $lineNo, $exception);
    }
}

#: END OF ./src/Exception/ParseException.php FILE
