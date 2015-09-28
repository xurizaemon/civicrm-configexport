<?php

/**
 *
 */

namespace Civi\API\ConfigManager;

/**
 *
 */
class ConfigManager {

  /**
   * Get ConfigManager export / import location on disk.
   *
   * @return string
   */
  function getDirectory() {
    return \CRM_Utils_File::baseFilePath() . 'ConfigAndLog/configmgr' . DIRECTORY_SEPARATOR;
  }

  /**
   * Get a directory to export entities of $entity_type to.
   *
   * @param string $entity_type
   *   Type of entity we are exporting.
   *
   * @return string
   */
  function getTypeDirectory(string $entity_type) {
    return ConfigManager::getDirectory() . strtolower($entity_type);
  }

  /**
   * Get a directory to export entities of $entity_type to.
   *
   * @param string $entity_type
   *   Type of entity we are exporting.
   *
   * @return string
   */
  function getYamlPath(array $params) {
    if (!isset($params['entity_type'])) {
      throw new \API_Exception(ts('Entity type is required.'));
    }
    if (!isset($params['uuid'])) {
      if (!isset($params['entity_id'])) {
        throw new \API_Exception(ts('UUID or Entity ID is required.'), 2900);
      }
      if (!$uuid = civicrm_api3('uuid', 'get', $params)) {
        throw new \API_Exception(ts('Unable to obtain UUID for %1', array('1' => print_r($params, 1))), 2900);
      }
      $params['uuid'] = $uuid['values']['uuid'];
    }
    return ConfigManager::getTypeDirectory($params['entity_type']) . DIRECTORY_SEPARATOR . $params['uuid'] . '.yml';
  }

}
