<?php

/**
 * @file
 * API for UUID.
 */

require 'vendor/autoload.php';

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * Uuid.Create API specification (optional).
 *
 * This is used for documentation and validation.
 *
 * @param array $spec
 *   description of fields supported by this API call.
 *
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_uuid_create_spec(array &$spec) {
  $spec['magicword']['api.required'] = 1;
}

/**
 * Uuid.Create API.
 *
 * @param array $params
 *   API parameters.
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 *
 * @throws API_Exception
 *   It's an API_Exception.
 */
function civicrm_api3_uuid_create(array $params) {
  if (array_key_exists('magicword', $params) && $params['magicword'] == 'sesame') {
    $return_values = array(
      12 => array('id' => 12, 'name' => 'Twelve'),
      34 => array('id' => 34, 'name' => 'Thirty four'),
      56 => array('id' => 56, 'name' => 'Fifty six'),
    );
    return civicrm_api3_create_success($return_values, $params, 'NewEntity', 'NewAction');
  }
  else {
    throw new API_Exception('Everyone knows that the magicword is "sesame"', 1234);
  }
}

/**
 * Uuid.Get API specification (optional).
 *
 * This is used for documentation and validation.
 *
 * @param array $spec
 *   Description of fields supported by this API call.
 *
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_uuid_get_spec(array &$spec) {
  $spec['entity_type']['api.required'] = 1;
  $spec['entity_id']['api.required'] = 1;
}

/**
 * Uuid.Get API - retrieve a UUID for a given entity.
 *
 * @param array $params
 *   API parameters.
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 *
 * @throws API_Exception
 *   It's an API Exception.
 */
function civicrm_api3_uuid_get(array $params) {
  $uuid = array();

  // Check things exist before agreeing to manage them.
  if (!$api = civicrm_api3($params['entity_type'], 'getsingle', array('id' => $params['entity_id']))) {
    throw new API_Exception(ts('Unable to obtain %1 with ID %2', array(1 => $params['entity_type'], 2 => $params['entity_id'])));
  }

  if (!$dao = _civicrm_api3_uuid_find_by_entity_ref($params['entity_type'], $params['entity_id'])) {
    $entry = array(
      'module' => 'civicrm_configexport',
      'entity_type' => $params['entity_type'],
      'entity_id' => $params['entity_id'],
      'name' => 'ConfigExport::' . $params['entity_type'] . '::' . $params['entity_id'],
      'uuid' => Uuid::uuid4()->toString(),
    );
    $insert = CRM_Utils_SQL_Insert::into('civicrm_managed')
      ->row($entry)
      ->toSQL();
    $dao = CRM_Core_DAO::executeQuery($insert);
    if (!$dao = _civicrm_api3_uuid_find_by_entity_ref($params['entity_type'], $params['entity_id'])) {
      throw new API_Exception('Unable to obtain UUID', 2900);
    }
  }
  $uuid[$dao->id] = array(
    'uuid' => $dao->uuid,
    'entity_type' => $dao->entity_type,
    'entity_id' => $dao->entity_id,
  );
  return civicrm_api3_create_success($uuid, $params, 'Uuid', 'get', $dao);
}

/**
 * Check for a UUID by entity type and ID.
 */
function _civicrm_api3_uuid_find_by_entity_ref($entity_type, $entity_id) {
  $query = CRM_Utils_SQL_Select::from('civicrm_managed m')
    ->select(array('m.id', 'm.uuid', 'm.entity_type', 'm.entity_id'))
    ->where('m.entity_id = @entity_id', array('@entity_id' => $entity_id))
    ->where('m.entity_type = @entity_type', array('@entity_type' => $entity_type))
    ->where('m.module = @module', array('@module' => 'civicrm_configexport'))
    ->toSQL();
  $dao = CRM_Core_DAO::executeQuery($query);
  if ($dao->fetch()) {
    return $dao;
  }
}
