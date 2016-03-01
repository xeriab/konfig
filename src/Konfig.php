<?php

namespace Exen\Konfig;

use Exen\Konfig\Exception\EmptyDirectoryException;
use Exen\Konfig\Exception\FileNotFoundException;
use Exen\Konfig\Exception\UnsupportedFormatException;

class Konfig extends AbstractKonfig
{
    /**
     * All file formats supported by Konfig
     *
     * @var array
     */
    private $_supportedFileParsers = [
        'Exen\Konfig\FileParser\Ini',
        'Exen\Konfig\FileParser\Json',
        'Exen\Konfig\FileParser\Php',
        'Exen\Konfig\FileParser\Toml',
        'Exen\Konfig\FileParser\Xml',
        'Exen\Konfig\FileParser\Yaml',
    ];

    /**
     * Loads a supported configuration file format.
     *
     * @param  string | array $path
     * @throws EmptyDirectoryException If `$path` is an empty directory
     */
    public function __construct($path = null)
    {
        $paths = $this->getValidPath($path);

        $this->data = [];

        foreach ($paths as $path) {
            // Get file information
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $parser = $this->getParser($ext);

            // Try and load file
            $this->data = array_replace_recursive($this->data, $parser->parse($path));
        } // END foreach

        parent::__construct($this->data);
    }

    /**
     * Static method for loading a Konfig instance.
     *
     * @param  string | array $path
     * @return Konfig
     */
    public static function load($path = null)
    {
        return new static($path);
    }

    /**
     * Gets a parser for a given file extension
     *
     * @param  string $ext
     * @return Konfig\FileParser\FileParserInterface
     * @throws UnsupportedFormatException If `$path` is an unsupported file format
     */
    private function getParser($ext = null)
    {
        $parser = null;

        foreach ($this->_supportedFileParsers as $fileParser) {
            // require_once 'FileParser/' . $fileParser . '.php';

            $tempParser = new $fileParser;

            if (in_array($ext, $tempParser->getSupportedFileExtensions($ext), true)) {
                $parser = $tempParser;
                break;
            }
        }

        // If none exist, then throw an exception
        if (is_null($parser)) {
            throw new UnsupportedFormatException('Unsupported configuration format');
        }

        return $parser;
    } // END OF getParser METHOD

    /**
     * Checks `$path` to see if it is either an array, a directory, or a file
     *
     * @param  string | array $path
     * @return array
     * @throws EmptyDirectoryException If `$path` is an empty directory
     * @throws FileNotFoundException If a file is not found at `$path`
     */
    private function getValidPath($path = null)
    {
        // If `$path` is an array
        if (is_array($path)) {
            $paths = [];

            foreach ($path as $unverifiedPath) {
                try {
                    // Check if `$unverifiedPath` is optional
                    // If it exists, then it's added to the list
                    // If it doesn't, it throws an exception which we catch
                    if ($unverifiedPath[0] !== '?') {
                        $paths = array_merge($paths, $this->getValidPath($unverifiedPath));
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

        // If `$path` is a directory
        if (is_dir($path)) {
            // $paths = @glob($path . '/*.*');
            $paths = @glob($path . '/*.{yaml,json,ini,xml,toml,yml,php,inc,php5,conf,cfg}', GLOB_BRACE);

            if (empty($paths)) {
                throw new EmptyDirectoryException("Configuration directory: [$path] is empty");
            }

            return $paths;
        }

        // If `$path` is not a file, throw an exception
        if (!file_exists($path)) {
            throw new FileNotFoundException("Configuration file: [$path] cannot be found");
        }

        return [$path];
    }
}

#: END OF ./Konfig.php FILE
