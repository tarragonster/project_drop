ALTER TABLE `user` ADD `device_os` VARCHAR(20) NULL DEFAULT NULL AFTER `subscription_id`,
ADD `app_version` VARCHAR(20) NULL DEFAULT NULL AFTER `device_os`,
ADD `build_number` VARCHAR(20) NULL DEFAULT NULL AFTER `app_version`;
