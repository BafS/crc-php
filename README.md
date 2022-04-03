# CRC-PHP

Flexible implementation of cyclic redundancy check (CRC) in PHP 8.0+.

## Usage

### Basic Usage

Example using CRC24/OPENPGP

```php
$encoder = CrcFactory::create(CrcFactory::CRC24_OPENPGP);
echo dechex($encoder->compute('test')); // f86ed0
```

### Recommended Usage (using memoization)

It is recommended to use [memoization](https://en.wikipedia.org/wiki/Memoization) to make the calculation faster (~10x, it varies from the input and the CRC used, see `examples/benchmark.php` for more details).

You can generate the table with `Encoder::generateTable()`.

```php
$encoder = CrcFactory::create(CrcFactory::CRC24_OPENPGP);

print_r($encoder->generateTable()); // [0x0, 0x864cfb, 0x8ad50d, 0xc99f6, 0x93e6e1, 0x15aa1a, 0x1933ec, ...]
```

You can see an example with a nicer output in `examples/generate-table.php`.

You should set this table during the bootstrapping of your application and/or in the service container.

```php
// Bootstrap
$table = [0x0, 0x864cfb, 0x8ad50d, 0xc99f6, 0x93e6e1, 0x15aa1a, 0x1933ec, 0x9f7f17, 0xa18139, ...];
$encoder = CrcFactory::create(CrcFactory::CRC24_OPENPGP, $table);

// Encoder will use the table to compute the hash
echo dechex($encoder->compute('test')); // f86ed0
```

### Advance Usage with Custom Parameters

It is possible to give custom parameters to the encoder to get any CRC.

```php
$encoder = CrcFactory::create(new Parameters(width: 8, poly: 0x07, init: 0x00));
echo dechex($encoder->compute('test')); // b9
```

You can also create parameters from a string.

```php
$encoder = CrcFactory::create(Parameters::createFromString('width=8 poly=0x07 init=0x00 refin=false refout=false xorout=0x00 check=0xf4 residue=0x00 name="CRC-8/SMBUS"'));
echo dechex($encoder->compute('test')); // b9
```

## Tests

- Unit tests: `./vendor/bin/phpunit --testdox --color tests`
- Code quality: `./vendor/bin/psalm`
