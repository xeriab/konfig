<?php

namespace Exen\Konfig\FileParser;

class AbstractFileParser implements FileParserInterface
{
    /**
     * Path to the config file
     *
     * @var string
     */
    protected $path = null;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

    public function getSupportedFileExtensions()
    {
    }

    public function parse($path)
    {
    }

}

#: END OF ./FileParser/AbstractFileParser.php FILE
