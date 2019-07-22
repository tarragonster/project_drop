ALTER TABLE `user` ADD `fb_token` VARCHAR(255) NULL DEFAULT NULL AFTER `facebook_id`,
ADD `fb_expired_at` INT(11) NOT NULL DEFAULT '0' AFTER `fb_token`;
