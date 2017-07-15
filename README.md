## Magento2 Code Generator

This module provide possibility to generate code via command line tool.

## Requirements

- Magento 2 (CE, EE) 2.1.0 and later
- PHP >=7.0

## Installation

Install the latest version with

```bash
$ composer require krifollk/module-code-generator
```

## Usage

Currently, module supports the next commands:

Generating module skeleton.
- 1-st param is module name. 
- 2-nd module version (not required, by default 0.1.0).
```bash
$ php bin/magento generate:module Config_Editor 0.2.0

Output:
File /var/www/magento2/app/code/Config/Editor/registration.php was generated.
File /var/www/magento2/app/code/Config/Editor/etc/module.xml was generated.
File /var/www/magento2/app/code/Config/Editor/composer.json was generated.
File /var/www/magento2/app/code/Config/Editor/Setup/InstallData.php was generated.
File /var/www/magento2/app/code/Config/Editor/Setup/InstallSchema.php was generated.
File /var/www/magento2/app/code/Config/Editor/Setup/Uninstall.php was generated.
File /var/www/magento2/app/code/Config/Editor/Setup/UpgradeData.php was generated.
File /var/www/magento2/app/code/Config/Editor/Setup/UpgradeSchema.php was generated.
```

Generating 'Model Triad' by DB table.
- 1-st param is module name.
- 2-nd entity name.
- 3-th table name.

```bash
$ php bin/magento generate:model:triad Config_Editor Config core_config_data

Output:
File /var/www/magento2/app/code/Config/Editor/Api/Data/ConfigInterface.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/ResourceModel/Config.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/Config.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/ResourceModel/Config/Collection.php was generated.
File /var/www/magento2/app/code/Config/Editor/Api/Data/ConfigSearchResultsInterface.php was generated.
File /var/www/magento2/app/code/Config/Editor/Api/ConfigRepositoryInterface.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/ConfigRepository.php was generated.
File /var/www/magento2/app/code/Config/Editor/etc/di.xml was generated.
```

Generating 'Crud' by DB table.
- 1-st param is module name.
- 2-nd entity name.
- 3-th table name.

```bash
$ php bin/magento generate:crud Config_Editor Config core_config_data

Output:
File /var/www/magento2/app/code/Config/Editor/Api/Data/ConfigInterface.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/ResourceModel/Config.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/Config.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/ResourceModel/Config/Collection.php was generated.
File /var/www/magento2/app/code/Config/Editor/Api/Data/ConfigSearchResultsInterface.php was generated.
File /var/www/magento2/app/code/Config/Editor/Api/ConfigRepositoryInterface.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/ConfigRepository.php was generated.
File /var/www/magento2/app/code/Config/Editor/etc/di.xml was generated.
File /var/www/magento2/app/code/Config/Editor/Model/UiComponent/Listing/Column/ConfigActions.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/Config/DataProvider.php was generated.
File /var/www/magento2/app/code/Config/Editor/Model/ResourceModel/Config/Grid/Collection.php was generated.
File /var/www/magento2/app/code/Config/Editor/view/adminhtml/ui_component/config_editor_config_form.xml was generated.
File /var/www/magento2/app/code/Config/Editor/view/adminhtml/ui_component/config_editor_config_listing.xml was generated.
File /var/www/magento2/app/code/Config/Editor/view/adminhtml/layout/config_editor_config_edit.xml was generated.
File /var/www/magento2/app/code/Config/Editor/view/adminhtml/layout/config_editor_config_index.xml was generated.
File /var/www/magento2/app/code/Config/Editor/view/adminhtml/layout/config_editor_config_new.xml was generated.
File /var/www/magento2/app/code/Config/Editor/Controller/Adminhtml/Config/Index.php was generated.
File /var/www/magento2/app/code/Config/Editor/etc/adminhtml/routes.xml was generated.
File /var/www/magento2/app/code/Config/Editor/etc/di.xml was generated.
File /var/www/magento2/app/code/Config/Editor/Controller/Adminhtml/Config/Edit.php was generated.
File /var/www/magento2/app/code/Config/Editor/Controller/Adminhtml/Config/NewAction.php was generated.
File /var/www/magento2/app/code/Config/Editor/Controller/Adminhtml/Config/Save.php was generated.
File /var/www/magento2/app/code/Config/Editor/Controller/Adminhtml/Config/Delete.php was generated.
File /var/www/magento2/app/code/Config/Editor/Controller/Adminhtml/Config/MassDelete.php was generated.
File /var/www/magento2/app/code/Config/Editor/Controller/Adminhtml/Config/InlineEdit.php was generated.
```

Generating 'Plugins' (Interactive mode)
- 1-st param is module name.

```bash
$ php bin/magento generate:plugin Config_Editor

Output:
Enter the name of the class for which you want to create plugin: \Magento\Cms\Controller\Index\Index
Enter the name of the plugin class (\Module\Name\ part not required) Default: \Config\Editor\Plugin\Magento\Cms\Controller\Index\Index:
+-----+-----------------+
| #id | Allowed methods |
+-----+-----------------+
| 0   | execute         |
| 1   | dispatch        |
| 2   | getActionFlag   |
| 3   | getRequest      |
| 4   | getResponse     |
+-----+-----------------+
Enter method ids and types of interception(a - after, b - before, ar - around)
for which you want to create plugin using next format: id:b-ar-a, id:a-b: 0:a-b-ar
+-------------+--------------------+
| Method Name | Interception types |
+-------------+--------------------+
| execute     | Before             |
|             | Around             |
|             | After              |
|             |                    |
+-------------+--------------------+
Is everything alright ? (y\n - yes by default)
File /var/www/magento2/app/code/Config/Editor/Plugin/Magento/Cms/Controller/Index/Index.php has been generated.
File /var/www/magento2/app/code/Config/Editor/etc/di.xml has been generated.
```

In additional, all commands supports --dir option where you can specify your custom module directory.

Ex: --dir=modules/module-some-dir

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/Krifollk/magento-code-generator/issues)

## Author

Rostyslav Tymoshenko

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
