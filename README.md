# Configuration Export for CiviCRM

## Status

This extension is in development.

## TODO

* Rename as "ConfigManagement" or something, because (duh!) Import is not Export, and we came here to Import too.
* Rename as "EntityManagement" or something, because (duh!) not everything is Config, and I realised we can export content like Contacts too. (Although the `civicrm_managed` table is gonna get *pretty weird* doing that.)
* Identify issues where entities must be unique in characteristics other than ID (eg, "Payment processor name must be unique").
* Identify issues where dumping / loading entities triggers mismatches (financial_type_id needs to be rewritten in import to connect to correct financial type).

## API additions

Get a UUID for something (this makes it a managed thing in passing). Probably want to be able to look for UUIDs without making entities managed?

    drush cvapi uuid.get entity_type=payment_processor_type entity_id=16

Export a something. Stores it to ConfigAndLog/configmanager/ENTITY_TYPE/UUID.yml

    drush cvapi configexport.export entity_type=contact entity_id=219

## Dependencies

Dependencies are exported as part of the parent object, like so:

    ---
    uuid: 557f9364-93f8-4598-b3ab-454beb60fb72
    title: Help Support CiviCRM!
    intro_text: >
      Do you love CiviCRM? Do you use CiviCRM?
      Then please support CiviCRM and
      Contribute NOW by trying out our new
      online contribution features!
    financial_type_id: 1
    payment_processor: 1
    currency: USD
    configmgr_dependencies:
      payment_processor:
        -
          uuid: 07ed40f0-4328-4eb3-b389-8301e49051d1
          domain_id: 1
          name: Test Processor
          payment_processor_type_id: 10
          url_site: http://dummy.com
          class_name: Payment_Dummy
          payment_type: 1
      financial_type:
        -
          uuid: f32219e5-4b5c-49d5-8de6-0e5791f46b90
          name: Donation
          is_active: 1

Nested dependencies are not yet handled, and dependent entities still export some details which they ought not to. We're into realms where we need to deal with specifics of CiviCRM now, eg "Two entities of type X may not share a name" and so forth.

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
