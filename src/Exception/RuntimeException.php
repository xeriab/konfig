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

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    private $parsedFile = null;
    private $parsedLine = 0;
    private $snippe = null;
    private $rawMessage = null;

    /**
     * Constructor.
     *
     * @param string     $message    The error message
     * @param int        $parsedLine The line where the error occurred
     * @param int        $snippet    The snippet of code near the problem
     * @param string     $parsedFile The file name where the error occurred
     * @param \Exception $previous   The previous exception
     * @codeCoverageIgnore
     */
    public function __construct(
        $message,
        $parsedLine = -1,
        $snippet = null,
        $parsedFile = null,
        Exception $previous = null
    ) {
        $this->parsedFile = $parsedFile;
        $this->parsedLine = $parsedLine;
        $this->snippet = $snippet;
        $this->rawMessage = $message;

        $this->updateRepr();

        parent::__construct($this->message, 0, $previous);
    }

    /**
     * Gets the snippet of code near the error.
     *
     * @return string The snippet of code
     * @codeCoverageIgnore
     */
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * Sets the snippet of code near the error.
     *
     * @param string $snippet The code snippet
     * @return void
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     */
    public function getParsedFile()
    {
        return $this->parsedFile;
    }

    /**
     * Sets the filename where the error occurred.
     *
     * @param string $parsedFile The filename
     * @codeCoverageIgnore
     */
    public function setParsedFile($parsedFile = null)
    {
        $this->parsedFile = $parsedFile;

        $this->updateRepr();
    }

    /**
     * Gets the line where the error occurred.
     *
     * @return int The file line
     * @codeCoverageIgnore
     */
    public function getParsedLine()
    {
        return $this->parsedLine;
    }

    /**
     * Sets the line where the error occurred.
     *
     * @param int $parsedLine The file line
     * @return void
     * @codeCoverageIgnore
     */
    public function setParsedLine($parsedLine = 0)
    {
        $this->parsedLine = $parsedLine;

        $this->updateRepr();
    }

    /**
     * @codeCoverageIgnore
     */
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

// END OF ./src/Exception/RuntimeException.php FILE
