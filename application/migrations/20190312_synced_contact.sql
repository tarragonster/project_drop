ALTER TABLE `user` ADD `synced_contact` TINYINT(1) NOT NULL DEFAULT '0' AFTER `google_id`;
ALTER TABLE `user` ADD `sync_skipped` TINYINT(1) NOT NULL DEFAULT '0' AFTER `synced_contact`;
