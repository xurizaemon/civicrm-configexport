<?php
require 'vendor/autoload.php';

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * Uuid.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_uuid_Get_spec(&$spec) {
  $spec['entity_type']['api.required'] = 1;
  $spec['entity_id']['api.required'] = 1;
}

/**
 * Uuid.Get API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_uuid_Get($params) {
  $uuid = array();

  if (!$dao = _civicrm_api3_uuid_find_by_entityref($params)) {
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
    print $dao->id;
    print_r($params);
    if (!$dao = _civicrm_api3_uuid_find_by_entityref($params)) {
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

function _civicrm_api3_uuid_find_by_entityref($params) {
  $query = CRM_Utils_SQL_Select::from('civicrm_managed m')
      ->select(array('m.id', 'm.uuid', 'm.entity_type', 'm.entity_id'))
      ->where('m.entity_id = @entity_id', array('@entity_id' => $params['entity_id']))
      ->where('m.entity_type = @entity_type', array('@entity_type' => $params['entity_type']))
      ->where('m.module = @module', array('@module' => 'civicrm_configexport'))
      ->toSQL();
  print $query;
  $dao = CRM_Core_DAO::executeQuery($query);
  if ($dao->fetch()) {
    return $dao;
  }
}
