<?php

require_once 'configexport.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function configexport_civicrm_config(&$config) {
  _configexport_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function configexport_civicrm_xmlMenu(&$files) {
  _configexport_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function configexport_civicrm_install() {
  _configexport_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function configexport_civicrm_uninstall() {
  _configexport_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function configexport_civicrm_enable() {
  _configexport_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function configexport_civicrm_disable() {
  _configexport_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function configexport_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _configexport_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function configexport_civicrm_managed(&$entities) {
  _configexport_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function configexport_civicrm_caseTypes(&$caseTypes) {
  _configexport_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function configexport_civicrm_angularModules(&$angularModules) {
_configexport_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function configexport_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _configexport_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
* Implements hook_civicrm_xmlMenu().
*
* @param $items array()
*
* @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
*/
function configexport_civicrm_drush_commands(&$items) {
 $items['civicrm-config-export'] = array(
   // explicit callback declaration and non-standard name to avoid collision with "sql-conf"
   'callback' => 'configexport_civicrm_config_export',
   'description' => 'TODO',
 );
}

/**
 * Implementation of command 'civicrm-sql-conf'
 */
function configexport_civicrm_config_export() {
  $conf = drush_sql_conf();
  // Before drush 6 drush_sql_conf already does drush_print_r, so it shouldn't
  // be called again.
  if (version_compare(DRUSH_VERSION, 6, '>=')) {
    drush_print_r($conf);
  }
}
