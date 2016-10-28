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
 * Runtime exception class
 *
 * @category Exception.
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    /**
     * Path to the parsed file.
     *
     * @var string $parsedFile
     *
     * @since 0.2.8
     */
    private $parsedFile = null;

    /**
     * Parsed line number.
     *
     * @var int $parsedLine
     *
     * @since 0.2.8
     */
    private $parsedLine = 0;

    /**
     * Snippet.
     *
     * @var string $snippet
     *
     * @since 0.2.8
     */
    private $snippet = null;

    /**
     * Raw error message.
     *
     * @var string $rawMessage
     *
     * @since 0.2.8
     */
    private $rawMessage = null;

    /**
     * Constructor.
     *
     * @param string    $message     Error message
     * @param int       $parsed_line Line where the error occurred
     * @param int       $snippet     Snippet of code near the problem
     * @param string    $parsed_file File name where the error occurred
     * @param Exception $previous    The previous exception
     *
     * @codeCoverageIgnore
     */
    public function __construct(
        $message,
        $parsed_line = -1,
        $snippet = null,
        $parsed_file = null,
        Exception $previous = null
    ) {
        $this->parsedFile = $parsed_file;
        $this->parsedLine = $parsed_line;
        $this->snippet = $snippet;
        $this->rawMessage = $message;

        $this->updateRepr();

        parent::__construct($this->message, 0, $previous);
    }

    /**
     * Gets the snippet of code near the error.
     *
     * @return             string The snippet of code
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
     *
     * @return             void Void
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
     * @return             string The filename
     * @codeCoverageIgnore
     */
    public function getParsedFile()
    {
        return $this->parsedFile;
    }

    /**
     * Sets the filename where the error occurred.
     *
     * @param string $parsed_file The filename
     *
     * @return             void Void
     * @codeCoverageIgnore
     */
    public function setParsedFile($parsed_file = null)
    {
        $this->parsedFile = $parsed_file;

        $this->updateRepr();
    }

    /**
     * Gets the line where the error occurred.
     *
     * @return int The file line
     *
     * @codeCoverageIgnore
     */
    public function getParsedLine()
    {
        return $this->parsedLine;
    }

    /**
     * Sets the line where the error occurred.
     *
     * @param int $parsed_line The file line
     *
     * @return             void Void
     * @codeCoverageIgnore
     */
    public function setParsedLine($parsed_line = 0)
    {
        $this->parsedLine = $parsed_line;

        $this->updateRepr();
    }

    /**
     * Update message.
     *
     * @return             void Void
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
                json_encode(
                    $this->parsedFile,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
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
