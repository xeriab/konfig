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

use ArrayAccess;
use Iterator;

/**
 * AbstractKonfig.
 *
 * Main Konfig abstract class.
 *
 * @category Main
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 *
 * @implements ArrayAccess
 * @implements Iterator
 * @implements KonfigInterface
 */
abstract class AbstractKonfig implements ArrayAccess, Iterator, KonfigInterface
{
    /**
     * Stores the configuration items.
     *
     * @var array Data
     *
     * @since 0.1.0
     */
    protected $data = [];

    /**
     * Caches the configuration data.
     *
     * @var array Cache
     *
     * @since 0.1.0
     */
    protected $cache = [];

    /**
     * Random value used as a not-found check in get().
     *
     * @var string
     *
     * @since 0.1.0
     */
    protected static $defaultCheckValue;

    /**
     * Constructor method and sets default options, if any.
     *
     * @param array $input Input
     */
    public function __construct($input)
    {
        $this->data = Arr::mergeAssoc($this->getDefaults(), $input);
        //$this->data = array_merge($this->getDefaults(), $input);
    }

    /**
     * Override this method in your own subclass to provide an array of default
     * options and values.
     *
     * @return array
     *
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    protected function getDefaults()
    {
        return [];
    }

    // KONFIGINTERFACE METHODS

    /**
     * {@inheritdoc}
     *
     * @return array Gets configuration items array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key Configuration item key name item
     *
     * @return bool Returns true if the configuration item key exists
     *
     * @since              0.1.0
     * @codeCoverageIgnore
     */
    public function has($key)
    {
        // Check if already cached
        if (Arr::get($this->cache, $key) || isset($this->cache[$key])) {
            return true;
        }

        $chunks = explode('.', $key);
        $root = $this->data;

        // Nested case
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
     * {@inheritdoc}
     *
     * @param string            $key     Configuration item key name item
     * @param string|array|null $default Default configuration
     *
     * @return string|array|null Default configuration
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            // return $this->cache[$key];
            return Arr::get($this->data, $key);
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key   Configuration item key name
     * @param mixed  $value Configuration item value
     *
     * @return void Void
     */
    public function set($key, $value)
    {
        $chunks = explode('.', $key);
        $root = &$this->data;
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

    /**
     * {@inheritdoc}
     *
     * @param string $key Configuration item key name
     *
     * @return             bool
     * @codeCoverageIgnore
     */
    public function delete($key)
    {
        if (isset($this->cache[$key])) {
            // unset($this->cache[$key]);
            Arr::delete($this->cache, $key);
        }

        return Arr::delete($this->data, $key);
    }

    // ARRAYACCESS METHODS

    /**
     * Gets a value using the offset as a key.
     *
     * @param string $offset Configuration item key name
     *
     * @return mixed Configuration item
     *
     * @since 0.1.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Checks if a key exists.
     *
     * @param string $offset Configuration item key name
     *
     * @return bool
     *
     * @since 0.1.0
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Sets a value using the offset as a key.
     *
     * @param string $offset Configuration item key name
     * @param mixed  $value  Configuration item value
     *
     * @return void Void
     * @since  0.1.0
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Deletes a key and its value.
     *
     * @param string $offset Configuration item key name
     *
     * @return void Void
     * @since  0.1.0
     */
    public function offsetUnset($offset)
    {
        $this->set($offset, null);
    }

    // ITERATOR METHODS

    /**
     * Tests whether the iterator's current index is valid.
     *
     * @return bool True if the current index is valid; false otherwise
     *
     * @since 0.1.0
     */
    public function valid()
    {
        return is_array($this->data) ? key($this->data) !== null : false;
    }

    /**
     * Returns the data array index referenced by its internal cursor.
     *
     * @return mixed The index referenced by the data array's internal cursor.
     * If the array is empty or undefined or there is no element at the cursor,
     * the function returns null.
     *
     * @since 0.1.0
     */
    public function key()
    {
        return is_array($this->data) ? key($this->data) : null;
    }

    /**
     * Returns the data array element referenced by its internal cursor.
     *
     * @return mixed The element referenced by the data array's internal cursor.
     * If the array is empty or there is no element at the cursor,
     * the function returns false. If the array is undefined, the function
     * returns null
     *
     * @since 0.1.0
     */
    public function current()
    {
        return is_array($this->data) ? current($this->data) : null;
    }

    /**
     * Moves the data array's internal cursor forward one element.
     *
     * @return mixed The element referenced by the data array's internal cursor
     * after the move is completed. If there are no more elements in the
     * array after the move, the function returns false. If the data array
     * is undefined, the function returns null.
     *
     * @since 0.1.0
     */
    public function next()
    {
        return is_array($this->data) ? next($this->data) : null;
    }

    /**
     * Moves the data array's internal cursor to the first element.
     *
     * @return mixed The element referenced by the data array's internal cursor
     * after the move is completed. If the data array is empty, the function
     * returns false. If the data array is undefined, the function returns null.
     *
     * @since 0.1.0
     */
    public function rewind()
    {
        return is_array($this->data) ? reset($this->data) : null;
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
        return 'Exen\Konfig\AbstractKonfig' . PHP_EOL;
    }
}

// END OF ./src/AbstractKonfig.php FILE
