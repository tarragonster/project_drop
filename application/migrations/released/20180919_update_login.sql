ALTER TABLE `user` ADD `facebook_id` VARCHAR(50) NULL DEFAULT NULL AFTER `password`,
ADD `google_id` VARCHAR(50) NULL DEFAULT NULL AFTER `facebook_id`;
