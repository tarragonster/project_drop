ALTER TABLE `user_picks` ADD `is_hidden` TINYINT(1) NOT NULL DEFAULT '0' AFTER `quote`;
ALTER TABLE `user_watch` ADD `is_hidden` TINYINT(1) NOT NULL DEFAULT '0' AFTER `start_time`;
ALTER TABLE `watch_list` ADD `is_hidden` TINYINT(1) NULL DEFAULT '0' AFTER `user_id`,
	ADD `added_at` INT(11) NOT NULL DEFAULT '0' AFTER `is_hidden`;
ALTER TABLE `episode_like` ADD `is_hidden` TINYINT(1) NOT NULL DEFAULT '0' AFTER `episode_id`;
ALTER TABLE `product_likes` ADD `is_hidden` TINYINT(1) NOT NULL DEFAULT '0' AFTER `product_id`;

