## Magento2 Code Generator

This module provide possibility to generate code via command line tool.

## Requirements

Magento 2 (CE, EE) 2.0.0 and later

## Installation

Install the latest version with

```bash
$ composer require krifollk/module-code-generator
```

## Usage

Currently, module supports the next commands:

Generating 'Model Triad' by DB table.
```bash
$ php bin/magento generate:model:triad Test/Generation QuoteAddress quote_address
```

1-st param is module name.
2-nd entity name.
3-th table name.

> Model (and interface) will be generated with all the necessary methods (getter, setter).

After executing this command will be generated the following files:
- Test/Generation/Api/Data/QuoteAddressInterface.php.
- Test/Generation/Model/QuoteAddressRepository.php
- Test/Generation/Api/QuoteAddressRepositoryInterface.php
- Test/Generation/Model/ResourceModel/QuoteAddress.php
- Test/Generation/Model/QuoteAddress.php
- Test/Generation/Model/ResourceModel/QuoteAddress/Collection.php

Generating module skeleton.
```bash
$ php bin/magento generate:module Test/Generation 0.1.0
```
1-st param is module name. 
2-nd module version (not required, by default 0.1.0).

After executing this command will be generated the following files:

- Test/Generation/registration.php
- Test/Generation/etc/module.xml

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/Krifollk/magento-code-generator/issues)

## Author

Rostyslav Tymoshenko


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details
