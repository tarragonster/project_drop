ALTER TABLE `user` ADD `stripe_customer` VARCHAR(50) NULL DEFAULT NULL AFTER `birthday`,
ADD `subscription_id` VARCHAR(50) NULL DEFAULT NULL AFTER `stripe_customer`,
ADD `current_period_start` INT(11) NOT NULL DEFAULT '0' AFTER `subscription_id`,
ADD `current_period_end` INT(11) NOT NULL DEFAULT '0' AFTER `current_period_start`,
ADD `canceled_at` INT(11) NOT NULL DEFAULT '0' AFTER `current_period_end`;

ALTER VIEW `user_view` AS
select `u`.`user_id` AS `user_id`,`u`.`user_name` AS `user_name`,`u`.`email` AS `email`,`u`.`birthday` AS `birthday`,`u`.`bio` AS `bio`,`u`.`full_name` AS `full_name`,`u`.`avatar` AS `avatar`,`u`.`level` AS `level`,`u`.`user_type` AS `user_type`, `u`.`current_period_start`, `u`.`current_period_end`, `u`.`canceled_at`, `u`.`facebook_id` AS `facebook_id`,`u`.`google_id` AS `google_id`,`u`.`last_login` AS `last_login`,`u`.`joined` AS `joined`,`u`.`status` AS `status`,`p`.`name` AS `activity`,`p`.`product_id` AS `product_id` from (`user` `u` left join `product` `p` on((`p`.`product_id` = `u`.`product_id`)));

ALTER TABLE `product` ADD `paywall_episode` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `jw_media_id`;

ALTER VIEW `product_view` AS select `p`.`product_id` AS `product_id`,`p`.`image` AS `image`,`p`.`background_img` AS `background_img`,`p`.`name` AS `name`,`p`.`total_time` AS `total_time`,`p`.`publish_year` AS `publish_year`,`p`.`short_bio` AS `short_bio`,`p`.`description` AS `description`,`p`.`trailler_url` AS `trailler_url`,`p`.`trailler_image` AS `trailler_image`,`p`.`jw_media_id` AS `jw_media_id`,`p`.`paywall_episode` AS `paywall_episode`,`p`.`priority` AS `priority`,`p`.`status` AS `status`,`p`.`rate_id` AS `rate_id`,`p`.`creators` AS `creators`,`fr`.`name` AS `rate_name` from (`product` `p` left join `film_rate` `fr` on((`fr`.`rate_id` = `p`.`rate_id`)));

