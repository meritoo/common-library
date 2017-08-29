# Meritoo Common Library
Useful classes, methods, extensions etc.

## Installation

Run [Composer](https://getcomposer.org) to install new package:

    ```bash
    $ composer require meritoo/common-library
    ```

> How to install Composer: https://getcomposer.org/download

## Usage

This package contains a lot of static methods, so usage is not so complicated. Just run the static method who would you like to use. Example:

```php
use Meritoo\Common\Utilities\Arrays;

$firstElement = Arrays::getFirstElement(['lorem' 'ipsum']);
// result: "lorem"
```

Enjoy!
