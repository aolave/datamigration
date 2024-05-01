# Omnipro_DataMigration Module

### Author
Cesar Robledo <cesar.robledo@omni.pro> || OmniPro Team

### Package
Omnipro_DataMigration

## Associated tickets
- https://omnipro.atlassian.net/browse/FDATLDC-1047
- https://omnipro.atlassian.net/browse/FDATLDC-1081
- https://omnipro.atlassian.net/browse/FDATLDC-1082
- https://omnipro.atlassian.net/browse/FDATLDC-1083
- https://omnipro.atlassian.net/browse/FDATLDC-1084
- https://omnipro.atlassian.net/browse/FDATLDC-1085

## Omnipro_DataMigration module by Omni.pro

    omnipro/module-data-migration

- [Main Functionalities](#markdown-header-main-functionalities)
- [Installation](#markdown-header-installation)
- [Specifications](#markdown-header-specifications)

## Main Functionalities
- Init "Data Migration" module base
- Create command to migrate customers "Data Migration"
- Create command to migrate address "Data Migration" 
- Create command to migrate products "Data Migration" 
- Create command to migrate orders "Data Migration"

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

- Unzip the zip file in `app/code/Omnipro`
- Enable the module by running `php bin/magento module:enable Omnipro_DataMigration`
- Apply database updates by running `php bin/magento setup:upgrade`\*
- Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer - NOT AVAILABLE YET
```bash
composer require omnipro/module-data-migration
```

### Copyright
Copyright (c) 2023 OmniPro (https://omni.pro/)

### Documentation
"Data Migration" module: [DOCUMENTATION]()

