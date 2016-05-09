# Magento2 code generator

This module provide possibility to generate code via command line tool.
Currently support the next commands:
```
1. bin/magento generate:model:triad Test/Generation QuoteAddress quote_address 
```
1-st param is module name. 2-nd entity name, 3-th table name.
After executing this command will be generated the following files:
- Test/Generation/Api/Data/QuoteAddressInterface.php.
- Test/Generation/Model/QuoteAddressRepository.php
- Test/Generation/Api/QuoteAddressRepositoryInterface.php
- Test/Generation/Model/ResourceModel/QuoteAddress.php
- Test/Generation/Model/QuoteAddress.php
- Test/Generation/Model/ResourceModel/QuoteAddress/Collection.php
