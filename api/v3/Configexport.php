<?php

/**
 * @file
 * Config Export for CiviCRM.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

require 'vendor/autoload.php';

use Civi\API\ConfigManager\ConfigManager;

/**
 * Configexport.create API specification (optional).
 *
 * This is used for documentation and validation.
 *
 * @param array $spec
 *   Description of fields supported by this API call.
 *
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_configexport_create_spec(array &$spec) {
  $spec['entity_type']['api.required'] = 1;
  $spec['entity_id']['api.required'] = 1;
}

/**
 * Configexport.create API.
 *
 * @param array $params
 *   CiviCRM API params array.
 *
 * @return array
 *   API result descriptor.
 *
 * @throws API_Exception
 *   Throws an API exception.
 */
function civicrm_api3_configexport_create(array $params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * Configexport.delete API.
 *
 * @param array $params
 *   CiviCRM API params.
 *
 * @return array
 *   API result descriptor.
 *
 * @throws API_Exception
 *   Throws an API exception.
 */
function civicrm_api3_configexport_delete(array $params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * Configexport.get API.
 *
 * @param array $params
 *   CiviCRM API array.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 *   Throws an API exception.
 */
function civicrm_api3_configexport_get(array $params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * Configexport.export API specification (optional).
 *
 * This is used for documentation and validation.
 *
 * @param array $spec
 *   Description of fields supported by this API call.
 *
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_configexport_export_spec(array &$spec) {
  $spec['entity_type']['api.required'] = 1;
  $spec['entity_id']['api.required'] = 1;
}

/**
 * Configexport.export API.
 *
 * @param array $params
 *   CiviCRM API array.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 *   Throws an API exception.
 */
function civicrm_api3_configexport_export(array $params) {
  $export = ConfigManager::getExportableData($params);

  $return_values = array(
    'uuid' => $export['uuid'],
    'entity_type' => $params['entity_type'],
    'entity_id' => $params['entity_id'],
    'yaml' => Spyc::YAMLDump($export),
  );

  $export_file = ConfigManager::getYamlPath(array('entity_type' => $params['entity_type'], 'uuid' => $export['uuid']));

  if (!is_dir(dirname($export_file))) {
    if (!mkdir(dirname($export_file), 0777, TRUE)) {
      throw new API_Exception(ts('Unable to write export to %1.', array(1 => $export_file)), 2902);
    }
  }
  if (!file_put_contents($export_file, $return_values['yaml'])) {
    throw new API_Exception(ts('Unable to write export to %1.', array(1 => $export_file)), 2903);
  }
  return civicrm_api3_create_success($return_values, $params, 'Configexport', 'Export');
}

/**
 * Configexport.export API specification (optional).
 *
 * This is used for documentation and validation.
 *
 * @param array $spec
 *   Description of fields supported by this API call.
 *
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_configexport_import_spec(array &$spec) {
  $spec['uuid']['api.required'] = 1;
  $spec['entity_type']['api.required'] = 1;
}

/**
 * Configexport.import API.
 *
 * @param array $params
 *   CiviCRM API array.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 *   Throws an API exception.
 */
function civicrm_api3_configexport_import(array $params) {
  $import_file = ConfigManager::getYamlPath($params);
  if (!file_exists($import_file)) {
    throw new API_Exception(ts('Unable to find exported entity: %1::%2', array(1 => $params['entity_type'], 2 => $params['uuid'])));
  }
  $entity = Spyc::YAMLLoad($import_file);
  if ($entity_id = civicrm_api3('uuid', 'entityid', $params)) {
    if ($api = civicrm_api3($params['entity_type'], 'get', array('id' => $entity_id['values']['entity_id']))) {
      if ($id = reset(array_keys($api['values']))) {
        // Update the entity.
        $entity['id'] = $id;
      }
    }
  }
  return civicrm_api3($params['entity_type'], 'create', $entity);
}
