# Konfig

Konfig is a simple configuration file loader library that supports INI, JSON, PHP, TOML, XML
and YML files.

## Requirements

Konfig requires PHP 5.4+, and suggests using [Yosymfony Toml Parser](https://github.com/yosymfony/Toml), [Piwik INI Parser](https://github.com/piwik/component-ini) and [Spyc YAML Library](https://github.com/mustangostang/spyc/).

## Installation

The supported way of installing Konfig is via Composer.

```sh
$ composer require kodeburner/konfig
```

## Usage

Konfig is designed to be very simple, lightweight and straightforward to use. All you can do with
it is load, get, and set.

### Loading files

The `Konfig` object can be created via the factory method `load()`, or
by direct instantiation:

```php
use Exen\Konfig\Konfig;

// Load a single file
$konf = Konfig::load('konfig.json');
$konf = new Konfig('konfig.json');

// Load values from multiple files
$konf = new Konfig(['konfig.json', 'konfig.xml']);

// Load all supported files in a directory
$konf = new Konfig(__DIR__ . '/konfig');

// Load values from optional files
$konf = new Konfig(['konfig.dist.json', '?konfig.json']);
```

Files are parsed and loaded depending on the file extension. Note that when
loading multiple files, entries with **duplicate keys will take on the value
from the last loaded file**.

When loading a directory, the path is `glob`ed and files are loaded in by
name alphabetically.

### Getting values

Getting values can be done in three ways. One, by using the `get()` method:

```php
// Get value using key
$debug = $konf->get('debug');

// Get value using nested key
$secret = $konf->get('security.secret');

// Get a value with a fallback
$ttl = $konf->get('app.timeout', 3000);
```

The second method, is by using it like an array:

```php
// Get value using a simple key
$debug = $konf['debug'];

// Get value using a nested key
$secret = $konf['security.secret'];

// Get nested value like you would from a nested array
$secret = $konf['security']['secret'];
```

The third method, is by using the `all()` method:

```php
// Get all values
$data = $konf->all();
```

### Setting values

Although Konfig supports setting values via `set()` or, via the
array syntax, **any changes made this way are NOT reflected back to the
source files**. By design, if you need to make changes to your
configuration files, you have to do it manually.

```php
$konf = Konfig::load('konfig.json');

// Sample value from our konfig file
assert($konf['secret'] == '123');

// Update konfig value to something else
$konf['secret'] = '456';

// Reload the file
$konf = Konfig::load('konfig.json');

// Same value as before
assert($konf['secret'] == '123');

// This will fail
assert($konf['secret'] == '456');
```

### Using with default values

Sometimes in your own projects you may want to use Konfig for storing
application settings, without needing file I/O. You can do this by extending
the `AbstractKonfig` class and populating the `getDefaults()` method:

```php
class MyKonfig extends AbstractKonfig
{
    protected function getDefaults()
    {
        return array(
            'host' => 'localhost',
            'port'    => 80,
            'servers' => array(
                'host1',
                'host2',
                'host3'
            ),
            'application' => array(
                'name'   => 'configuration',
                'secret' => 's3cr3t'
            )
        );
    }
}
```

### Examples of supported configuration files

Examples of simple, valid configuration files can be found [here](tests/mocks/pass).


## Testing

``` bash
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Security

If you discover any security related issues, please email [kodeburner@gmail.com](mailto:kodeburner@gmail.com?subject=[SECURITY] Konfig Security Issue) instead of using the issue tracker.


## Credits

- [Xeriab Nabil](https://github.com/xeriab)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
