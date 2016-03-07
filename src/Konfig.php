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
namespace Exen\Konfig;

use Exen\Konfig\Exception\EmptyDirectoryException;
use Exen\Konfig\Exception\FileNotFoundException;
use Exen\Konfig\Exception\UnsupportedFileFormatException;

final class Konfig extends AbstractKonfig
{
    /**
     * @var FileParser[] $fileParsers Array of file parsers objects
     * @since 0.1
     */
    protected $fileParsers;

    /**
     * Stores loaded configuration files
     *
     * @var array $loadedFiles Array of loaded configuration files
     * @since 0.1
     */
    protected static $loadedFiles = [];

    /**
     * Loads a supported configuration file format.
     *
     * @param  string|array|mixed $path String file | configuration array | Konfig instance
     * @throws EmptyDirectoryException If `$path` is an empty directory
     */
    public function __construct($path, array $parsers = [])
    {
        $this->setFileParsers($parsers);

        $paths = $this->getValidPath($path);

        $this->data = [];

        foreach ($paths as $path) {
            // Get file information
            $ext    = pathinfo($path, PATHINFO_EXTENSION);
            $parser = $this->getParser($ext);

            // Try and load file
            $this->data = array_replace_recursive($this->data, $parser->parse($path));

            self::$loadedFiles[$path] = true;
        }

        parent::__construct($this->data);
    }

    /**
     * Static method for loading a Konfig instance.
     *
     * @param  string|array|mixed $path string file | configuration array | Konfig instance
     * @return Konfig
     */
    public static function load($path = null)
    {
        return new static($path);
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
     * Static method for getting all Konfig keys.
     *
     * @return array
     */
    public static function keys()
    {
        // @TODO: Fix this soon
    }

    /**
     * @return FileParser[]
     * @since 0.1
     */
    public function getFileParsers()
    {
        return $this->fileParsers;
    }

    /**
     * @return void
     * @since 0.1
     */
    protected function addFileParser(FileParser $fileParser)
    {
        $this->fileParsers[] = $fileParser;
    }

    /**
     * @return void
     * @since 0.1
     */
    protected function setFileParsers(array $fileParsers = [])
    {
        if (empty($fileParsers)) {
            $fileParsers = [
                new FileParser\Xml(),
                new FileParser\Ini(),
                new FileParser\Json(),
                new FileParser\Yaml(),
                new FileParser\Neon(),
                new FileParser\Toml(),
                new FileParser\Php(),
            ];
        }

        $this->fileParsers = [];

        foreach ($fileParsers as $fileParser) {
            $this->addFileParser($fileParser);
        }
    }

    /**
     * Gets a parser for a given file extension
     *
     * @param  string $ext
     * @return Konfig\FileParser
     * @throws UnsupportedFileFormatException If `$path` is an unsupported file format
     */
    private function getParser($ext)
    {
        $parser = null;

        if (empty($ext)) {
            // @TODO: Throw an exception.
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
            throw new UnsupportedFileFormatException('Unsupported configuration format');
        }

        return $parser;
    }

    /**
     * Gets an array of paths
     *
     * @param array $path
     * @return array
     * @throws FileNotFoundException If a file is not found in `$path`
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

    /**
     * Checks `$path` to see if it is either an array, a directory, or a file
     *
     * @param  string|array $path
     * @return array
     * @throws EmptyDirectoryException If `$path` is an empty directory
     * @throws FileNotFoundException If a file is not found at `$path`
     */
    private function getValidPath($path = null)
    {
        // If `$path` is array
        if (is_array($path)) {
            return $this->pathFromArray($path);
        }

        // If `$path` is a directory
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');

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

    /**
     * @return string
     * @since 0.1
     */
    public function __toString()
    {
        return 'Konfig';
    }
}

#: END OF ./src/Konfig.php FILE
