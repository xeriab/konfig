<?php

namespace Exen\Konfig;

use ArrayAccess;
use Iterator;

/**
 * Constant to prevent loading Konfig's PHP
 * configuration files from outside Konfig.
 */
define('EXEN_KONFIG_FILE', true);

abstract class AbstractKonfig implements ArrayAccess, Iterator, KonfigInterface
{
    /**
     * Stores loaded configuration files
     *
     * @var array $loadedFiles Array of loaded configuration files
     */
    protected $loadedFiles = [];

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
        return [];
    }

    #: KonfigInterface Methods

    public function getAll()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        // Check if already cached
        if (isset($this->cache[$key])) {
            return true;
        }

        $segments = explode('.', $key);
        $root = $this->data;

        // nested case
        foreach ($segments as $segment) {
            if (array_key_exists($segment, $root)) {
                $root = $root[$segment];
                continue;
            } else {
                return false;
            }
        }

        // Set cache for the given key
        $this->cache[$key] = $root;

        return true;
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

    #: Iterator Methods

    /**
     * Tests whether the iterator's current index is valid
     *
     * @return bool True if the current index is valid; false otherwise
     */
    public function valid()
    {
        return (is_array($this->data) ? key($this->data) !== null : false);
    }

    /**
     * Returns the data array index referenced by its internal cursor
     *
     * @return mixed The index referenced by the data array's internal cursor.
     * If the array is empty or undefined or there is no element at the cursor,
     * the function returns null
     */
    public function key()
    {
        return (is_array($this->data) ? key($this->data) : null);
    }

    /**
     * Returns the data array element referenced by its internal cursor
     *
     * @return mixed The element referenced by the data array's internal cursor.
     * If the array is empty or there is no element at the cursor,
     * the function returns false. If the array is undefined, the function
     * returns null
     */
    public function current()
    {
        return (is_array($this->data) ? current($this->data) : null);
    }

    /**
     * Moves the data array's internal cursor forward one element
     *
     * @return mixed The element referenced by the data array's internal cursor
     * after the move is completed. If there are no more elements in the
     * array after the move, the function returns false. If the data array
     * is undefined, the function returns null
     */
    public function next()
    {
        return (is_array($this->data) ? next($this->data) : null);
    }

    /**
     * Moves the data array's internal cursor to the first element
     *
     * @return mixed The element referenced by the data array's internal cursor
     * after the move is completed. If the data array is empty, the function
     * returns false. If the data array is undefined, the function returns null
     */
    public function rewind()
    {
        return (is_array($this->data) ? reset($this->data) : null);
    }
}

#: END OF ./AbstractKonfig.php FILE
