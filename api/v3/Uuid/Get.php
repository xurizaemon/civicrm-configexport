<?php

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
  $sql = "SELECT uuid FROM civicrm_managed WHERE entity_type = %1 AND entity_id = %2";

  $qParams = array(
    1 => array($params['entity_type'], 'String'),
    2 => array($params['entity_id'], 'Integer'),
  );

  $uuid = array();
  $dao = CRM_Core_DAO::executeQuery($sql, $qParams);
  if ($dao->fetch()) {
    $uuid[$dao->id] = array(
      'uuid' => $dao->uuid,
      'entity_type' => $dao->entity_type,
      'entity_id' => $dao->entity_id,
    );
    return civicrm_api3_create_success($uuid, $params, 'Uuid', 'get', $dao);
  } else {
    throw new API_Exception(/*errorMessage*/ '', /*errorCode*/ 1234);
  }
}
