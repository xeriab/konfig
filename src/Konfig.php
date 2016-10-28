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

namespace Exen\Konfig;

use Exen\Konfig\Exception\Exception;
use Exen\Konfig\Exception\EmptyDirectoryException;
use Exen\Konfig\Exception\FileNotFoundException;
use Exen\Konfig\Exception\UnsupportedFileFormatException;

/**
 * Main Konfig class.
 *
 * @category Main
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @extends AbstractKonfig
 */
final class Konfig extends AbstractKonfig
{
    /**
     * Array of file parsers objects.
     *
     * @var array|null
     *
     * @since 0.1.0
     */
    protected $fileParsers;

    /**
     * Stores loaded configuration files.
     *
     * @var array Array of loaded configuration files
     *
     * @since 0.1.0
     */
    protected static $loadedFiles = [];

    /**
     * Array of loaded data.
     *
     * @var array|null
     *
     * @since 0.1.0
     */
    protected static $loadedData = null;

    /**
     * Loads a supported configuration file format.
     *
     * @param string|array|mixed $path      String file | configuration array
     * | Konfig instance | configuration array | Konfig instance
     * @param array              $parsers   Parsers
     * @param bool               $overwrite Whether to overwrite existing values
     * @param bool               $cache     Allow caching
     *
     * @throws EmptyDirectoryException If `$path` is an empty directory
     */
    public function __construct(
        $path = null,
        array $parsers = [],
        $overwrite = false,
        $cache = true
    ) {
        $this->setFileParsers($parsers);

        $paths = $this->getValidPath($path);

        $this->data = [];

        foreach ($paths as $path) {
            // Get file information
            $info = pathinfo($path);
            // $info  = pathinfo($path, PATHINFO_EXTENSION);
            $parts = explode('.', $info['basename']);
            $ext = array_pop($parts);

            if ($ext === 'dist') {
                $ext = array_pop($parts);
            }

            $parser = $this->getParser($ext);

            // Try and load file
            $this->data = array_replace_recursive(
                $this->data,
                (array) $parser->parse($path)
            );

            self::$loadedFiles[$path] = true;
        }

        self::$loadedData = $this->data;

        parent::__construct($this->data);
    }

    /**
     * Static method for loading a Konfig instance.
     *
     * @param string|array|mixed $path      string file | configuration array
     *                                      | Konfig instance
     * @param array              $parsers   Parsers to use with Konfig
     * @param bool               $overwrite Whether to overwrite existing values
     * @param bool               $cache     Allow caching
     *
     * @return Konfig
     */
    public static function load(
        $path = null,
        array $parsers = [],
        $overwrite = false,
        $cache = true
    ) {
        return new static($path, $parsers);
    }

    /**
     * Static method for getting loaded Konfig files.
     *
     * @return array
     */
    public static function loaded()
    {
        return self::$loadedFiles;
    }

    /**
     * Get file parsers.
     *
     * @return FileParser[]
     *
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public function getFileParsers()
    {
        return $this->fileParsers;
    }

    /**
     * Add file parsers.
     *
     * @param FileParser $fileParser Parser
     *
     * @return             void Void
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    protected function addFileParser(FileParser $fileParser)
    {
        $this->fileParsers[] = $fileParser;
    }

    /**
     * Set file parsers.
     *
     * @param array $fileParsers Parsers array
     *
     * @return             void Void
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    protected function setFileParsers(array $fileParsers = [])
    {
        if (empty($fileParsers)) {
            $fileParsers = [
                // Default parsers
                new FileParser\Xml(),
                new FileParser\Ini(),
                new FileParser\Json(),
                new FileParser\Php(),

                // Additional parsers
                new FileParser\Yaml(),
                new FileParser\Neon(),
                new FileParser\Toml(),
                new FileParser\Properties(),
            ];
        }

        $this->fileParsers = [];

        foreach ($fileParsers as $fileParser) {
            $this->addFileParser($fileParser);
        }
    }

    /**
     * Gets a parser for a given file extension.
     *
     * @param string|null $ext File extension
     *
     * @return FileParser
     *
     * @throws Exception                      If `$ext` is empty
     * @throws UnsupportedFileFormatException If `$path`
     * is an unsupported file format
     */
    private function getParser($ext = null)
    {
        $parser = null;

        if (empty($ext)) {
            throw new Exception('Files with empty extensions are not allowed');
        }

        $fileParsers = $this->getFileParsers();

        foreach ($fileParsers as $fileParser) {
            if (in_array($ext, $fileParser->getSupportedFileExtensions(), true)) {
                $parser = $fileParser;
                break;
            }
        }

        // If none exist, then throw an exception
        if (is_null($parser)) {
            throw new UnsupportedFileFormatException(
                'Unsupported configuration format'
            );
        }

        return $parser;
    }

    /**
     * Gets an array of paths.
     *
     * @param array $path Path to analyze and handle
     *
     * @return array
     *
     * @throws             FileNotFoundException If a file is not found in `$path`
     * @codeCoverageIgnore
     */
    private function pathFromArray($path)
    {
        $paths = [];

        foreach ($path as $unverifiedPath) {
            try {
                // Check if `$unverifiedPath` is optional
                // If it exists, then it's added to the list
                // If it doesn't, it throws an exception which we catch
                if ($unverifiedPath[0] !== '?') {
                    $paths = array_merge(
                        $paths,
                        $this->getValidPath($unverifiedPath)
                    );

                    continue;
                }

                $optionalPath = ltrim($unverifiedPath, '?');

                $paths = array_merge($paths, $this->getValidPath($optionalPath));
            } catch (FileNotFoundException $e) {
                // If `$unverifiedPath` is optional, then skip it
                if ($unverifiedPath[0] === '?') {
                    continue;
                }

                // Otherwise rethrow the exception
                throw $e;
            }
        }

        return $paths;
    }

    /**
     * Checks `$path` to see if it is either an array, a directory, or a file.
     *
     * @param string|array $path Path to analyze and handle
     *
     * @return array
     *
     * @throws EmptyDirectoryException If `$path` is an empty directory
     * @throws FileNotFoundException   If a file is not found at `$path`
     */
    private function getValidPath($path)
    {
        // If `$path` is array
        if (is_array($path)) {
            return $this->pathFromArray($path);
        }

        // If `$path` is a directory
        if (is_dir($path)) {
            $paths = glob($path.'/*.*');

            if (empty($paths)) {
                throw new EmptyDirectoryException(
                    "Configuration directory: [$path] is empty"
                );
            }

            return $paths;
        }

        // If `$path` is not a file, throw an exception
        if (!file_exists($path)) {
            throw new FileNotFoundException(
                "Configuration file: [$path] cannot be found"
            );
        }

        return [$path];
    }

    /**
     * __toString.
     *
     * @return             string
     * @since              0.1.2
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return 'Exen\Konfig\Konfig' . PHP_EOL;
    }
}

// END OF ./src/Konfig.php FILE
