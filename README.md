# PHP Runtime

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg)](https://php.net/)
[![build](https://github.com/akondas/php-runtime/actions/workflows/build.yml/badge.svg)](https://github.com/akondas/php-runtime/actions/workflows/build.yml)
[![Latest Stable Version](https://poser.pugx.org/akondas/php-runtime/v/stable?format=flat)](https://packagist.org/packages/akondas/php-runtime)
![GitHub](https://img.shields.io/github/license/akondas/php-runtime)


PHP Runtime class provides methods to get total and free memory and number of available processors.

## Usage

```bash
composer require akondas/php-runtime
```

```php
use Akondas\Runtime;

$runtime = new Runtime();

$runtime->totalMemory();
$runtime->freeMemory();
$runtime->availableProcessors();
```

## License

PHPRuntime is released under the MIT Licence. See the bundled LICENSE file for details.

## Author

[Arkadiusz Kondas](https://twitter.com/ArkadiuszKondas)
