# Configuration Export for CiviCRM

## Status

This extension is in development.

## TODO

* Rename as "ConfigManagement" or something, because (duh!) Import is not Export, and we came here to Import too.
* Rename as "EntityManagement" or something, because (duh!) not everything is Config, and I realised we can export content like Contacts too. (Although the `civicrm_managed` table is gonna get *pretty weird* doing that.)

## API additions

Get a UUID for something (this makes it a managed thing in passing). Probably want to be able to look for UUIDs without making entities managed?

    drush cvapi uuid.get entity_type=payment_processor_type entity_id=16

Export a something. Stores it to ConfigAndLog/configmanager/ENTITY_TYPE/UUID.yml

    drush cvapi configexport.export entity_type=contact entity_id=219

## Installation

If you've got the code from Github, you'll also need to run `composer install` in the extension directory to install required libraries.

If distributed, this step will be removed. For now, consider it a "you must be this high to ride" constraint :)

When the extension is installed, core schema will be modified when a `civicrm_managed.uuid` column will be added.

## Disabling

**Entities managed by this module will automatically be removed when it is disabled.**

## Uninstallation

Uninstalling the module will remove the `civicrm_managed.uuid` column and `civicrm_configexport` table.

## Questions

* Modifying core schema is discouraged, and maybe we could / should do this without modifying civicrm_managed table ... but this will do for now.
