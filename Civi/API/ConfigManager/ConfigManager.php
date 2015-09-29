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
   * Get an exportable data structure from an entity.
   *
   * @param array $params
   *   Parameters (entity_type, uuid).
   *
   * @return array
   */
  function getExportableData(array $params) {
    $api_params = array(
      'id' => $params['entity_id'],
    );
    $uuid_params = $params;
    if (!$uuid = civicrm_api3('uuid', 'get', $uuid_params)) {
      throw new API_Exception(ts('Unable to obtain UUID for %1', array('1' => print_r($params, 1))), 2900);
    }
    if (!$api = civicrm_api3($params['entity_type'], 'getsingle', $api_params)) {
      throw new API_Exception(ts('Unable to obtain %1 with ID %2', array(1 => $params['entity_type'], 2 => $params['entity_id'])));
    }
    $uuid = $uuid['values']['uuid'];

    // Prepend UUID, remove numeric ID.
    $export = array_merge(array('uuid' => $uuid), $api);
    unset($export['id']);

    // If there are entities this entity depends on, add them to the UUID.
    if ($dependencies = ConfigManager::getDependencyTypes($params['entity_type'])) {
      foreach ($dependencies as $dep_column) {
        $dep_type = preg_replace('/_id$/', '', $dep_column);
        if (!empty($api[$dep_column])) {
          $dep_params = array(
            'entity_type' => $dep_type,
            'entity_id' => $api[$dep_column],
          );
          if ($dep = ConfigManager::getExportableData($dep_params)) {
            $export['configmgr_dependencies'][$dep_type][] = $dep;
          }
        }
      }
    }

    return $export;
  }

  /**
   * Get a directory to export entities of $entity_type to.
   *
   * @param array $params
   *   Parameters (entity_type, uuid).
   *
   * @return string
   */
  function getYamlPath(array $params) {
    if (!isset($params['entity_type'])) {
      throw new \API_Exception(ts('Entity type is required.'));
    }
    if (!isset($params['uuid'])) {
      if (!isset($params['entity_id'])) {
        throw new \API_Exception(ts('UUID or Entity ID is required, got %1', array('1' => print_r($params,1))), 2900);
      }
      if (!$uuid = civicrm_api3('uuid', 'get', $params)) {
        throw new \API_Exception(ts('Unable to obtain UUID for %1', array('1' => print_r($params, 1))), 2900);
      }
      $params['uuid'] = $uuid['values']['uuid'];
    }
    return ConfigManager::getTypeDirectory($params['entity_type']) . DIRECTORY_SEPARATOR . $params['uuid'] . '.yml';
  }

  /**
   * Get information about which entities can be dependent of an entity.
   *
   * @TODO Move to per-type classes instead of a big switch?
   *
   * @TODO What about dependent information where entity_id or contact_id joins to this entity? (eg civicrm_email.contact_id)
   *
   * @param string $entity_type
   *    Type of entity being exported.
   *
   * @return array
   */
  function getDependencyTypes(string $entityType) {
    switch ($entityType) {
      case 'contribution_page':
        return array(
          // 'payment_processor', // VARCHAR(128) of ^A-separated payment_processor_id's
          'financial_type_id', // FOREIGN KEY (`financial_type_id`) REFERENCES `civicrm_financial_type`
          'campaign_id', // FOREIGN KEY (`campaign_id`) REFERENCES `civicrm_campaign`
          'created_id', // FOREIGN KEY (`created_id`) REFERENCES `civicrm_contact` (`id`)
        );

      default:
        // Nothing will come of nothing.
    }
  }

}
