<?php

namespace Exen\Konfig;

use ArrayAccess;
use Iterator;

abstract class AbstractKonfig implements ArrayAccess, Iterator, KonfigInterface
{
    /**
     * Stores loaded configuration files
     *
     * @var array $loadedFiles Array of loaded configuration files
     */
    protected $loadedFiles = [];

    /**
     * Stores the configuration items
     *
     * @var array | null
     */
    protected $items = null;

    /**
     * Caches the configuration items
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Constructor method and sets default options, if any
     *
     * @param array $items
     */
    public function __construct($items)
    {
        $this->items = array_merge($this->getDefaults(), $items);
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
        return $this->items;
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

        $chunks = explode('.', $key);
        $root = $this->items;

        // nested case
        foreach ($chunks as $chunk) {
            if (array_key_exists($chunk, $root)) {
                $root = $root[$chunk];
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
        if ($this->has($key)) {
            return $this->cache[$key];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $chunks = explode('.', $key);
        $root = &$this->items;
        $cacheKey = '';

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($chunks)) {
            if ($cacheKey != '') {
                $cacheKey .= '.';
            }

            $cacheKey .= $part;

            if (!isset($root[$part]) && count($chunks)) {
                $root[$part] = [];
            }

            $root = &$root[$part];

            // Unset all old nested cache

            if (isset($this->cache[$cacheKey])) {
                unset($this->cache[$cacheKey]);
            }

            // Unset all old nested cache in case of array
            if (count($chunks) == 0) {
                foreach ($this->cache as $cacheLocalKey => $cacheValue) {
                    if (substr($cacheLocalKey, 0, strlen($cacheKey)) === $cacheKey) {
                        unset($this->cache[$cacheLocalKey]);
                    }
                }
            }
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
        return $this->has($offset);
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
        return (is_array($this->items) ? key($this->items) !== null : false);
    }

    /**
     * Returns the items array index referenced by its internal cursor
     *
     * @return mixed The index referenced by the items array's internal cursor.
     * If the array is empty or undefined or there is no element at the cursor,
     * the function returns null
     */
    public function key()
    {
        return (is_array($this->items) ? key($this->items) : null);
    }

    /**
     * Returns the items array element referenced by its internal cursor
     *
     * @return mixed The element referenced by the items array's internal cursor.
     * If the array is empty or there is no element at the cursor,
     * the function returns false. If the array is undefined, the function
     * returns null
     */
    public function current()
    {
        return (is_array($this->items) ? current($this->items) : null);
    }

    /**
     * Moves the items array's internal cursor forward one element
     *
     * @return mixed The element referenced by the items array's internal cursor
     * after the move is completed. If there are no more elements in the
     * array after the move, the function returns false. If the items array
     * is undefined, the function returns null
     */
    public function next()
    {
        return (is_array($this->items) ? next($this->items) : null);
    }

    /**
     * Moves the items array's internal cursor to the first element
     *
     * @return mixed The element referenced by the items array's internal cursor
     * after the move is completed. If the items array is empty, the function
     * returns false. If the items array is undefined, the function returns null
     */
    public function rewind()
    {
        return (is_array($this->items) ? reset($this->items) : null);
    }
}

#: END OF ./AbstractKonfig.php FILE
