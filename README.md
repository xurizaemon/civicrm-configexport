# Configuration Export for CiviCRM

## Status

This extension is in development.

## Installation

If you've got the code from Github, you'll also need to run `composer install` from the extension directory to install required libraries.

If distributed, this step will be removed. For now, consider it a "you must be this high to ride" constraint :)

When the extension is installed, core schema will be modified when a `civicrm_managed.uuid` column will be added.

## Disabling

**Entities managed by this module will automatically be removed when it is disabled.**

## Uninstallation

Uninstalling the module will remove the `civicrm_managed.uuid` column and `civicrm_configexport` table.

## Questions

* Modifying core schema is discouraged, and maybe we could / should do this without modifying civicrm_managed table ... but this will do for now.
