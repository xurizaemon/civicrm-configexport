<?php

class CRM_Configexport_BAO_Uuid extends CRM_Configexport_DAO_Uuid {

  /**
   * Create a new Uuid based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Configexport_DAO_Uuid|NULL
   *
  public static function create($params) {
    $className = 'CRM_Configexport_DAO_Uuid';
    $entityName = 'Uuid';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */
}
