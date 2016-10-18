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

namespace Exen\Konfig\FileParser;

use Exen\Konfig\Arr;
use Exen\Konfig\Utils;
use Exen\Konfig\Exception\KonfigException as Exception;
use Exen\Konfig\Exception\ParseException;

/**
 * Properties
 * Konfig's Java-Properties parser class.
 *
 * @category FileParser
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements Exen\Konfig\FileParser\AbstractFileParser
 */
class Properties extends AbstractFileParser
{
    protected $parsedFile;

    /**
     * Loads a PROPERTIES file as an array.
     *
     * @param string $path File path
     *
     * @throws ParseException If there is an error parsing PROPERTIES file
     *
     * @return array The parsed data
     *
     * @since 0.2.4
     */
    public function parse($path)
    {
        $this->loadFile($path);

        $data = $this->getProperties();

        if (!$data || empty($data) || !is_array($data)) {
            throw new ParseException(
                [
                'message' => 'Error parsing PROPERTIES file',
                'file' => $path,
                ]
            );
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @return array Supported extensions
     *
     * @since 0.1.0
     */
    public function getSupportedFileExtensions()
    {
        return ['properties'];
    }

    /**
     * {@inheritdoc}
     *
     * @return array The exteacted data
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public function extractData()
    {
        $analysis = [];

        // First pass, we categorize each line
        foreach ($this->parsedFile as $lineNb => $line) {
            if (Utils::stringStart('#', $line)) {
                $analysis[$lineNb] = ['comment', trim(substr($line, 1))];

                continue;
            }

            // Property name, check for escaped equal sign
            if (substr_count($line, '=') > substr_count($line, '\=')) {
                $temp = explode('=', $line, 2);
                $temp = Utils::trimArrayElements($temp);

                if (count($temp) === 2) {
                    $temp[1] = Utils::removeQuotes($temp[1]);

                    $analysis[$lineNb] = ['property', $temp[0], $temp[1]];
                }

                unset($temp);

                continue;
            }

            // Multiline data
            if (substr_count($line, '=') === 0) {
                $analysis[$lineNb] = ['multiline', '', $line];
                continue;
            }
        }

        // Second pass, we associate comments to entities
        $counter = Utils::getNumberLinesMatching('comment', $analysis);

        while ($counter > 0) {
            foreach ($analysis as $lineNb => $line) {
                if ($line[0] === 'comment'
                    && isset($analysis[$lineNb + 1][0])
                    && $analysis[$lineNb + 1][0] === 'comment'
                ) {
                    $analysis[$lineNb][1] .= ' '.$analysis[$lineNb + 1][1];
                    $analysis[$lineNb + 1][0] = 'erase';

                    break;
                } elseif ($line[0] === 'comment'
                    && isset($analysis[$lineNb + 1][0])
                    && $analysis[$lineNb + 1][0] === 'property'
                ) {
                    $analysis[$lineNb + 1][3] = $line[1];
                    $analysis[$lineNb][0] = 'erase';
                }
            }

            $counter = Utils::getNumberLinesMatching('comment', $analysis);
            $analysis = $this->deleteFields('erase', $analysis);
        }

        // Third pass, we merge multiline strings

        // We remove the backslashes at end of strings if they exist
        $analysis = Utils::stripBackslashes($analysis);

        // Count # of multilines
        $counter = Utils::getNumberLinesMatching('multiline', $analysis);

        while ($counter > 0) {
            foreach ($analysis as $lineNb => $line) {
                if ($line[0] === 'multiline'
                    && isset($analysis[$lineNb - 1][0])
                    && $analysis[$lineNb - 1][0] === 'property'
                ) {
                    $analysis[$lineNb - 1][2] .= ' ' . trim($line[2]);
                    $analysis[$lineNb][0] = 'erase';
                    break;
                }
            }

            $counter = Utils::getNumberLinesMatching('multiline', $analysis);
            $analysis = $this->deleteFields('erase', $analysis);
        }

        // Step 4, we clean up strings from escaped characters in properties
        $analysis = $this->unescapeProperties($analysis);

        // Step 5, we only have properties now, remove redondant field 0
        foreach ($analysis as $key => $value) {
            if (preg_match('/^[1-9][0-9]*$/', $value[2])) {
                $value[2] = intval($value[2]);
            }

            array_splice($analysis[$key], 0, 1);
        }

        return $analysis;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $analysis Configuration items
     *
     * @return array The configuration items
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    private function unescapeProperties($analysis)
    {
        foreach ($analysis as $key => $value) {
            $analysis[$key][2] = str_replace('\=', '=', $value[2]);
        }

        return $analysis;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $field    Field name
     * @param array  $analysis Configuration items
     *
     * @return array Configuration items after deletion
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    private function deleteFields($field, $analysis)
    {
        foreach ($analysis as $key => $value) {
            if ($value[0] === $field) {
                unset($analysis[$key]);
            }
        }

        return array_values($analysis);
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null $file File path
     *
     * @return array Configuration items
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    public function getProperties($file = null)
    {
        if ($file && !is_null($file)) {
            $this->loadFile($file);
        }

        $source = $this->extractData();
        $data = [];

        foreach ($source as $value) {
            Arr::set($data, $value[0], $value[1]);
        }

        unset($this->parsedFile);

        return $data;
    }

    /**
     * Loads in the given file and parses it.
     *
     * @param string|bool|null $file File to load
     *
     * @return array The parsed file data
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function loadFile($file = null)
    {
        $this->file = is_file($file) ? $file : false;

        $contents = $this->parseVars(Utils::getContent($this->file));

        if ($this->file && !is_null($file)) {
            $this->parsedFile = Utils::fileContentToArray($contents);
        } else {
            $this->parsedFile = false;
        }
    }

    /**
     * Returns the formatted configuration file contents.
     *
     * @param array $contents configuration array
     *
     * @return string formatted configuration file contents
     *
     * @since              0.2.4
     * @codeCoverageIgnore
     */
    protected function exportFormat($contents = null)
    {
        throw new Exception(
            'Saving configuration to `Properties` is not supported at this time'
        );
    }
}

// END OF ./src/FileParser/Properties.php FILE
