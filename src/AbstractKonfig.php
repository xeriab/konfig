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

use Iterator;
use ArrayAccess;
use Exen\Konfig\Utils;

abstract class AbstractKonfig implements ArrayAccess, Iterator, KonfigInterface
{
    /**
     * Stores the configuration items
     *
     * @var array | null
     * @since 0.1
     */
    protected $configData = null;

    /**
     * Caches the configuration configData
     *
     * @var array
     * @since 0.1
     */
    protected $configCache = [];

    /**
     * @var array $itemCache the dot-notated item cache
     * @since 0.1
     */
    static $itemCache = [];

    /**
     * @var string $defaultCheckValue Random value used as a not-found check in get()
     * @since 0.1
     */
    static $defaultCheckValue;

    /**
     * Constructor method and sets default options, if any
     *
     * @param array $configData
     * @since 0.1
     */
    public function __construct($configData)
    {
        // $this->configData = array_merge($this->getDefaults(), $configData);
        $this->configData = Utils::arrMerge($this->getDefaults(), $configData);
    }

    /**
     * Override this method in your own subclass to provide an array of default
     * options and values
     *
     * @codeCoverageIgnore
     * @return array
     * @since 0.1
     */
    protected function getDefaults()
    {
        return [];
    }

    #: KonfigInterface Methods

    public function getAll()
    {
        return $this->configData;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        // Check if already cached
        if (isset($this->configCache[$key])) {
            return true;
        }

        $chunks = explode('.', $key);
        $root = $this->configData;

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
        $this->configCache[$key] = $root;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->configCache[$key];
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $chunks = explode('.', $key);
        $root = &$this->configData;
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

            if (isset($this->configCache[$cacheKey])) {
                unset($this->configCache[$cacheKey]);
            }

            // Unset all old nested cache in case of array
            if (count($chunks) == 0) {
                foreach ($this->configCache as $cacheLocalKey => $cacheValue) {
                    if (substr($cacheLocalKey, 0, strlen($cacheKey)) === $cacheKey) {
                        unset($this->configCache[$cacheLocalKey]);
                    }
                }
            }
        }

        // Assign value at target node
        $this->configCache[$key] = $root = $value;
    }

    #: ArrayAccess Methods

    /**
     * Gets a value using the offset as a key
     *
     * @param  string $offset
     * @return mixed
     * @since 0.1
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
     * @since 0.1
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
     * @since 0.1
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
     * @since 0.1
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
     * @since 0.1
     */
    public function valid()
    {
        return (is_array($this->configData) ? key($this->configData) !== null : false);
    }

    /**
     * Returns the configData array index referenced by its internal cursor
     *
     * @return mixed The index referenced by the configData array's internal cursor.
     * If the array is empty or undefined or there is no element at the cursor,
     * the function returns null
     * @since 0.1
     */
    public function key()
    {
        return (is_array($this->configData) ? key($this->configData) : null);
    }

    /**
     * Returns the configData array element referenced by its internal cursor
     *
     * @return mixed The element referenced by the configData array's internal cursor.
     * If the array is empty or there is no element at the cursor,
     * the function returns false. If the array is undefined, the function
     * returns null
     * @since 0.1
     */
    public function current()
    {
        return (is_array($this->configData) ? current($this->configData) : null);
    }

    /**
     * Moves the configData array's internal cursor forward one element
     *
     * @return mixed The element referenced by the configData array's internal cursor
     * after the move is completed. If there are no more elements in the
     * array after the move, the function returns false. If the configData array
     * is undefined, the function returns null
     * @since 0.1
     */
    public function next()
    {
        return (is_array($this->configData) ? next($this->configData) : null);
    }

    /**
     * Moves the configData array's internal cursor to the first element
     *
     * @return mixed The element referenced by the configData array's internal cursor
     * after the move is completed. If the configData array is empty, the function
     * returns false. If the configData array is undefined, the function returns null
     * @since 0.1
     */
    public function rewind()
    {
        return (is_array($this->configData) ? reset($this->configData) : null);
    }
}

#: END OF ./src/AbstractKonfig.php FILE
