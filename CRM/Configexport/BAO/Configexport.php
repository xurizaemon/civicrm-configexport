<?php

class CRM_Configexport_BAO_Configexport extends CRM_Configexport_DAO_Configexport {

  /**
   * Create a new Configexport based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Configexport_DAO_Configexport|NULL
   *
  public static function create($params) {
    $className = 'CRM_Configexport_DAO_Configexport';
    $entityName = 'Configexport';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */
}
