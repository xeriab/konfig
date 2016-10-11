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

namespace Exen\Konfig\Exception;

class ParseException extends ErrorException
{
    private $parsedFile;
    private $parsedLine;
    private $snippet;
    private $rawMessage;

    /**
     * Constructor.
     *
     * @param array      $error    The error array
     */
    public function __construct(array $error)
    {
        $message   = isset($error['message']) ? $error['message'] : 'There was an error parsing the file';
        $code      = isset($error['code']) ? $error['code'] : 0;
        $severity  = isset($error['type']) ? $error['type'] : 1;
        $fileName  = isset($error['file']) ? $error['file'] : __FILE__;
        $lineNo    = isset($error['line']) ? $error['line'] : __LINE__;
        $exception = isset($error['exception']) ? $error['exception'] : null;
        $snippet   = isset($error['snippet']) ? $error['snippet'] : null;
        $previous  = isset($error['previous']) ? $error['previous'] : null;

        $this->parsedFile = $fileName;
        $this->parsedLine = $lineNo;
        $this->snippet = $snippet;
        $this->rawMessage = $message;

        $this->updateRepr();

        // parent::__construct(
        //     $this->message,
        //     0,
        //     $previous
        // );

        parent::__construct(
            $message,
            $code,
            $severity,
            $fileName,
            $lineNo,
            $exception
        );
    }

    /**
     * Gets the snippet of code near the error.
     *
     * @return string The snippet of code
     */
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * Sets the snippet of code near the error.
     *
     * @param string $snippet The code snippet
     */
    public function setSnippet($snippet)
    {
        $this->snippet = $snippet;

        $this->updateRepr();
    }

    /**
     * Gets the filename where the error occurred.
     *
     * This method returns null if a string is parsed.
     *
     * @return string The filename
     */
    public function getParsedFile()
    {
        return $this->parsedFile;
    }

    /**
     * Sets the filename where the error occurred.
     *
     * @param string $parsedFile The filename
     */
    public function setParsedFile(string $parsedFile = null)
    {
        $this->parsedFile = $parsedFile;

        $this->updateRepr();
    }

    /**
     * Gets the line where the error occurred.
     *
     * @return int The file line
     */
    public function getParsedLine()
    {
        return $this->parsedLine;
    }

    /**
     * Sets the line where the error occurred.
     *
     * @param int $parsedLine The file line
     */
    public function setParsedLine(int $parsedLine = 0)
    {
        $this->parsedLine = $parsedLine;

        $this->updateRepr();
    }

    private function updateRepr()
    {
        $this->message = $this->rawMessage;

        $dot = false;

        if ('.' === substr($this->message, -1)) {
            $this->message = substr($this->message, 0, -1);
            $dot = true;
        }

        if (null !== $this->parsedFile) {
            $this->message .= sprintf(
                ' in %s',
                json_encode($this->parsedFile, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }

        if ($this->parsedLine >= 0) {
            $this->message .= sprintf(' at line %d', $this->parsedLine);
        }

        if ($this->snippet) {
            $this->message .= sprintf(' (near "%s")', $this->snippet);
        }

        if ($dot) {
            $this->message .= '.';
        }
    }
}

// END OF ./src/Exception/ParseException.php FILE
