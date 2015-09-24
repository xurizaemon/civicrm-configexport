-- Add UUID column to civicrm_managed.
ALTER TABLE civicrm_managed
 ADD COLUMN uuid char(64);

-- Add config export table.
CREATE TABLE IF NOT EXISTS civicrm_configexport (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  uuid CHAR(36) NOT NULL COMMENT 'Unique identifier',
  configuration TEXT COMMENT 'Exported configuration',
  PRIMARY KEY (`id`)
);
