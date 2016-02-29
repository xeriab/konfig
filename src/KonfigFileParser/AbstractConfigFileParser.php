<?php

namespace Exen\Konfig\KonfigFileParser;

class AbstractKonfigFileParser implements IKonfigFileParser
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
}

#: END OF ./KonfigFileParser/AbstractKonfigFileParser.php FILE
