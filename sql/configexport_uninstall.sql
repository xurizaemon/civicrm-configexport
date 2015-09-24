-- Remove UUID column from civicrm_managed.
ALTER TABLE civicrm_managed
 DROP COLUMN uuid;

-- Remove config export table.
DROP TABLE IF EXISTS civicrm_configexport;
