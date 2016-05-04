# Magento2 code generator
This module provide possibility to generate code via command line tool.
Currently support the next commands:
```
1. bin/magento generate:model:triad quote_address Test_Generation QuoteAddress
1-st param is table name. 2-nd module name, 3-th entity name.
After executing this command will be generated the following files:
- Test/Generation/Api/Data/QuoteAddressInterface.php.
- Test/Generation/Model/QuoteAddressRepository.php
- Test/Generation/Api/QuoteAddressRepositoryInterface.php
- Test/Generation/Model/ResourceModel/QuoteAddress.php
- Test/Generation/Model/QuoteAddress.php
- Test/Generation/Model/ResourceModel/QuoteAddress/Collection.php
```

