<?php

namespace Exen\Konfig;

interface KonfigInterface
{
    /**
     * Function for setting configuration values
     *
     * @param string $key The configuration key to set
     * @param mixed $value The configuration value
     * @return void
     */
    public function set($key, $value);

    /**
     * Gets configuration setting using a simple or nested key.
     * Nested keys are similar to JSON paths that use the dot
     * dot notation.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Getting all configuration values.
     *
     * @return array | object
     */
    public function getAll();

    /**
     * Checking if configuration values exist, using
     * either simple or nested keys.
     *
     * @param string $key The key to check for
     *
     * @return array
     */
    public function has($key);
}

#: END OF ./KonfigInterface.php FILE
