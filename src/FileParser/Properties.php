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

use Exen\Konfig\Arr;
use Exen\Konfig\Utils;
use Exen\Konfig\Exception\Exception;
use Exen\Konfig\Exception\ParseException;

class Properties extends AbstractFileParser
{
    protected $parsedFile;

    /**
     * {@inheritDoc}
     * Loads a PROPERTIES file as an array
     *
     * @throws ParseException If there is an error parsing PROPERTIES file
     * @since 0.2.4
     */
    public function parse($path)
    {
        $this->loadFile($path);

        $data = $this->getProperties();

        if (!$data) {
            throw new ParseException([
                'message' => 'Error parsing PROPERTIES file',
                'file' => $path
            ]);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedFileExtensions()
    {
        return ['properties'];
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function extractData()
    {
        $analysis = [];

        // First pass, we categorize each line
        foreach ($this->parsedFile as $lineNb => $line) {
            if (Utils::stringStart('#', $line)) {
                $analysis[$lineNb] = [
                    'comment',
                    trim(substr($line, 1))
                ];

                continue;
            }

            // Property name, check for escaped equal sign
            if (substr_count($line, '=') > substr_count($line, '\=')) {
                $temp = explode('=', $line, 2);
                $temp = Utils::trimArrayElements($temp);

                if (count($temp) === 2) {
                    $temp[1] = Utils::removeQuotes($temp[1]);
                    
                    $analysis[$lineNb] = [
                        'property',
                        $temp[0],
                        $temp[1]
                    ];
                }

                unset($temp);

                continue;
            } else {
                break;
            }

            // Multiline data
            if (substr_count($line, '=') === 0) {
                $analysis[$lineNb] = [
                    'multiline',
                    '',
                    $line
                ];

                continue;
            }
        }

        // Second pass, we associate comments to entities
        $counter = Utils::getNumberLinesMatching('comment', $analysis);

        while ($counter > 0) {
            foreach ($analysis as $lineNb => $line) {
                if ($line[0] === 'comment' &&
                    isset($analysis[$lineNb + 1][0]) &&
                    $analysis[$lineNb + 1][0] === 'comment') {
                    $analysis[$lineNb][1] .= ' ' . $analysis[$lineNb + 1][1];
                    $analysis[$lineNb + 1][0] = 'erase';

                    break;
                } elseif ($line[0] === 'comment' &&
                    isset($analysis[$lineNb + 1][0]) &&
                    $analysis[$lineNb + 1][0] === 'property') {
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
                    && $analysis[$lineNb - 1][0] === 'property') {
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
            array_splice($analysis[$key], 0, 1);
        }

        return $analysis;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     * @since 0.2.4
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
     * @param   string  $file File to load
     * @return  array
     * @since 0.2.4
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
     * @param   array   $content  configuration array
     * @return  string  formatted configuration file contents
     * @since 0.2.4
     * @codeCoverageIgnore
     */
    protected function exportFormat($contents = null)
    {
        throw new \Exception('Saving configuration to `Properties` is not supported at this time');
    }
}

// END OF ./src/FileParser/Properties.php FILE
