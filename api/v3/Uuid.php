<?php

require 'vendor/autoload.php';

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * Uuid.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_uuid_create_spec(&$spec) {
  $spec['magicword']['api.required'] = 1;
}

/**
 * Uuid.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_uuid_create($params) {
  if (array_key_exists('magicword', $params) && $params['magicword'] == 'sesame') {
    $returnValues = array( // OK, return several data rows
      12 => array('id' => 12, 'name' => 'Twelve'),
      34 => array('id' => 34, 'name' => 'Thirty four'),
      56 => array('id' => 56, 'name' => 'Fifty six'),
    );
    // ALTERNATIVE: $returnValues = array(); // OK, success
    // ALTERNATIVE: $returnValues = array("Some value"); // OK, return a single value

    // Spec: civicrm_api3_create_success($values = 1, $params = array(), $entity = NULL, $action = NULL)
    return civicrm_api3_create_success($returnValues, $params, 'NewEntity', 'NewAction');
  } else {
    throw new API_Exception(/*errorMessage*/ 'Everyone knows that the magicword is "sesame"', /*errorCode*/ 1234);
  }
}

/**
 * Uuid.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_uuid_get_spec(&$spec) {
    $spec['entity_type']['api.required'] = 1;
    $spec['entity_id']['api.required'] = 1;
}

/**
 * Uuid.Get API - retrieve a UUID for a given entity.
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_uuid_Get($params)
{
    $uuid = array();

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
            throw new API_Exception('Blah blah', 1234);
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
function _civicrm_api3_uuid_find_by_entity_ref($entityType, $entityId)
{
    $query = CRM_Utils_SQL_Select::from('civicrm_managed m')
        ->select(array('m.id', 'm.uuid', 'm.entity_type', 'm.entity_id'))
        ->where('m.entity_id = @entity_id', array('@entity_id' => $entityId))
        ->where('m.entity_type = @entity_type', array('@entity_type' => $entityType))
        ->where('m.module = @module', array('@module' => 'civicrm_configexport'))
        ->toSQL();
    $dao = CRM_Core_DAO::executeQuery($query);
    if ($dao->fetch()) {
        return $dao;
    }
}
