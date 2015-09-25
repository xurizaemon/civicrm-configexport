<?php

/**
 * @file
 * Config Export for CiviCRM.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

require 'vendor/autoload.php';

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
  $api_params = array(
    'id' => $params['entity_id'],
  );
  $uuid_params = $params;
  if (!$uuid = civicrm_api3('uuid', 'get', $uuid_params)) {
    throw new API_Exception(ts('Unable to obtain UUID for %1', array('1' => print_r($params, 1))), 2900);
  }
  print_r($uuid);
  if (!$api = civicrm_api3($params['entity_type'], 'getsingle', $api_params)) {
    throw new API_Exception(ts('Unable to obtain %1 with ID %2', array(1 => $params['entity_type'], 2 => $params['entity_id'])));
  }
  // Prepend UUID.
  $export = array_merge(array('uuid' => $uuid), $api);
  unset($export['id']);
  $return_values = array(
    'uuid' => $uuid,
    'entity_type' => $params['entity_type'],
    'entity_id' => $params['entity_id'],
    'yaml' => Spyc::YAMLDump($export),
  );
  $export_dir = _configexport_get_directory() . strtolower($params['entity_type']);
  $export_file = $export_dir . DIRECTORY_SEPARATOR . $uuid . '.yml';
  if (!is_dir($export_dir)) {
    if (!mkdir($export_dir, 0777, TRUE)) {
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
  // Hmm ... so it might be easier if we had a BAO here?

}
