# Konfig

[![Latest version][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Konfig is a simple configuration loader library that supports INI, JSON, NEON, PHP, TOML, XML
and YML files.

## Requirements

Konfig requires PHP 5.4+, and suggests using [Yosymfony Toml Parser](https://github.com/yosymfony/Toml), [Nette NEON](https://github.com/nette/neon) and [Symfony YAML](https://github.com/symfony/Yaml).

## Installation

The supported way of installing Konfig is via Composer.

```sh
$ composer require xeriab/konfig
```

## Usage

Konfig is designed with simplicity in mind and it is lightweight and straightforward to use. All you can do with
it is load, get, set and delete.

### Loading files

The `Konfig` object can be created via the factory method `load()`, or
by direct instantiation:

```php
use Exen\Konfig\Konfig;

// Load a single file
$config = Konfig::load('konfig.json');
$config = new Konfig('konfig.json');

// Load values from multiple files
$config = new Konfig(['konfig.json', 'konfig.xml']);

// Load all supported files in a directory
$config = new Konfig(__DIR__ . '/konfig');

// Load values from optional files
$config = new Konfig(['konfig.dist.json', 'konfig.json']);
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
$debug = $config->get('debug');

// Get value using nested key
$secret = $config->get('security.secret');

// Get a value with a fallback
$ttl = $config->get('app.timeout', 3000);
```

The second method, is by using it like an array:

```php
// Get value using a simple key
$debug = $config['debug'];

// Get value using a nested key
$secret = $config['security.secret'];

// Get nested value like you would from a nested array
$secret = $config['security']['secret'];
```

The third method, is by using the `all()` method:

```php
// Get all values
$data = $config->all();
```

### Setting values

Although Konfig supports setting values via `set()` or, via the
array syntax, **any changes made this way are NOT reflected back to the
source files**. By design, if you need to make changes to your
configuration files, you have to do it manually.

```php
$config = Konfig::load('konfig.json');

// Sample value from our konfig file
assert($config['secret'] == '123');

// Update konfig value to something else
$config['secret'] = '456';

// Reload the file
$config = Konfig::load('konfig.json');

// Same value as before
assert($config['secret'] == '123');

// This will fail
assert($config['secret'] == '456');
```

### Using with default values

Sometimes in your own projects you may want to use Konfig for storing
application settings, without needing file I/O. You can do this by extending
the `AbstractKonfig` class and populating the `getDefaults()` method:

```php

use Exen\Konfig\AbstractKonfig;

class MyKonfig extends AbstractKonfig
{
    protected function getDefaults()
    {
        return [
            'host' => 'localhost',
            'port' => 80,
            'servers' => [
                'host1',
                'host2',
                'host3',
            ],
            'app' => [
                'name' => 'konfig',
                'secret' => 'secret',
            ],
        ];
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


## Credits

- [Xeriab Nabil](https://github.com/xeriab)


## Contributors

- [Nashwan Doaqan](https://github.com/nash-ye)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/xeriab/konfig.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/xeriab/konfig/master.svg?style=flat-square
[ico-scrutinizer]: https://scrutinizer-ci.com/g/xeriab/konfig/badges/coverage.png?b=master
[ico-code-quality]: https://scrutinizer-ci.com/g/xeriab/konfig/badges/quality-score.png?b=master
[ico-downloads]: https://img.shields.io/packagist/dt/xeriab/konfig.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/xeriab/konfig
[link-license]: http://xeriab.mit-license.org
[link-travis]: https://travis-ci.org/xeriab/konfig
[link-scrutinizer]: https://scrutinizer-ci.com/g/xeriab/konfig/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/xeriab/konfig
[link-downloads]: https://packagist.org/packages/xeriab/konfig
