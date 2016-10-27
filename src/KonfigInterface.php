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

/**
 * Main Konfig interface
 *
 * @category Main
 * @package  Konfig
 * @author   Xeriab Nabil (aka KodeBurner) <kodeburner@gmail.com>
 * @license  https://raw.github.com/xeriab/konfig/master/LICENSE MIT
 * @link     https://xeriab.github.io/projects/konfig
 */
interface KonfigInterface
{
    /**
     * Function for setting configuration values.
     *
     * @param string $key   The configuration key to set
     * @param mixed  $value The configuration value
     *
     * @return void Void
     */
    public function set($key, $value);

    /**
     * Gets configuration setting using a simple or nested key.
     * Nested keys are similar to JSON paths that use the dot
     * dot notation.
     *
     * @param string $key     Configuration item key name
     * @param mixed  $default Default configuration
     *
     * @return mixed
     *
     * @since 0.1.0
     */
    public function get($key, $default = null);

    /**
     * Getting all configuration values.
     *
     * @return array|object
     *
     * @since 0.1.0
     */
    public function all();

    /**
     * Checking if configuration values exist, using
     * either simple or nested keys.
     *
     * @param string $key The key to check for
     *
     * @return bool
     *
     * @since 0.1.0
     */
    public function has($key);

    /**
     * Deletes configuration item (key & value).
     *
     * @param string $key The key to delete
     *
     * @return array
     *
     * @since 0.2.0
     */
    public function delete($key);

    /*
     * Returns configuration group
     *
     * @return mixed
     * @since  0.2.4
     */
    // public function group();

    /*
     * Save configuration
     *
     * @param mixed $contents Configuration content to save
     *
     * @return mixed
     * @since  0.2.4
     */
    // public function save($contents = null);
}

// END OF ./src/KonfigInterface.php FILE
