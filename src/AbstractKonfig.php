<?php

namespace Exen\Konfig;

abstract class AbstractKonfig implements \ArrayAccess, IKonfig
{
    /**
     * Stores the configuration data
     *
     * @var array | null
     */
    protected $data = null;

    /**
     * Caches the configuration data
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Constructor method and sets default options, if any
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = array_merge($this->getDefaults(), $data);
    }

    /**
     * Override this method in your own subclass to provide an array of default
     * options and values
     *
     * @return array
     * @codeCoverageIgnore
     */
    protected function getDefaults()
    {
        //return array();
        return [];
    }

    #: IKonfig Methods

    public function getAll()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        // Check if already cached
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $segments = explode('.', $key);
        $root = $this->data;

        // nested case
        foreach ($segments as $part) {
            if (isset($root[$part])) {
                $root = $root[$part];
                continue;
            } else {
                $root = $default;
                break;
            }
        }

        // whatever we have is what we needed
        return ($this->cache[$key] = $root);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $segments = explode('.', $key);
        $root = &$this->data;

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($segments)) {
            if (!isset($root[$part]) && count($segments)) {
                $root[$part] = array();
            }

            $root = &$root[$part];
        }

        // Assign value at target node
        $this->cache[$key] = $root = $value;
    }

    #: ArrayAccess Methods

    /**
     * Gets a value using the offset as a key
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Checks if a key exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return !is_null($this->get($offset));
    }

    /**
     * Sets a value using the offset as a key
     *
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Deletes a key and its value
     *
     * @param  string $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->set($offset, null);
    }
}

#: END OF ./AbstractKonfig.php FILE
