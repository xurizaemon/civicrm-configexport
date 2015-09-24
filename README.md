# Configuration Export for CiviCRM



## What exists already?

There are multiple existing paths we could take with this:

* Robyn's Civi Synch extension.
* Features CiviCRM is an abandoned project to provide Features support.
* CiviCRM Entity exposes CiviCRM objects to Drupal, but doesn't try to offer export.
* Civix contains some code to export custom objects to XML.
* CiviCRM contains code to import exported objects from XML.

### Robyn's Civi Synch module

Robyn can speak to this better so I didn't research it too much. From a skim of the code I see that it exports many objects which are available via the API; I didn't see whether it also imports.

### Features CiviCRM

Codebase shows a lot of custom code per each object type supported, which suggests ongoing maintenance as new objects appear in CiviCRM space. Also, apparently it didn't work out for the maintainer. That's a red flag to me.

On the other hand, while this is probably the most complex implementation, using Features suggests that thought may have gone into idempotency more in this path.

### CiviCRM Entity

CiviCRM Entity I think takes a more dynamic approach to exposing CiviCRM objects than Features CiviCRM. It's possible that this might allow CiviCRM Entity to be better at supporting new CiviCRM objects out of the box.

### Civix

`civix` allows export of a very limited number of types (Profiles, Custom Groups and Fields I believe) to XML. This allows for an extension to provision its own components, but only for a very limited number, and probably not for update - just install.

### Custom extensions & Managed Entities

CiviCRM allows for custom extensions to manage certain entities. This handles export but doesn't deal with the logic of associating related entities (eg that a new event rego page uses the just-created new payment processor). It MAY be that the XML importer handles this a bit better?

### hook_civicrm_managed

CiviCRM has the `civicrm_managed` table which stores data about entities managed by extensions. This can be used to trigger update of managed entities into a CiviCRM instance.
