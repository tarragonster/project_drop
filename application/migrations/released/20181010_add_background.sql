ALTER TABLE `product` ADD `background_img` VARCHAR(255) NULL DEFAULT NULL AFTER `image`;

DROP VIEW IF EXISTS `product_view`;
CREATE VIEW `product_view` AS
select `p`.`product_id` AS `product_id`,`p`.`image` AS `image`,`p`.`background_img` AS `background_img`,`p`.`name` AS `name`,`p`.`total_time` AS `total_time`,`p`.`publish_year` AS `publish_year`,`p`.`short_bio` AS `short_bio`,`p`.`description` AS `description`,`p`.`trailler_url` AS `trailler_url`,`p`.`trailler_image` AS `trailler_image`,`p`.`jw_media_id` AS `jw_media_id`,`p`.`priority` AS `priority`,`p`.`status` AS `status`,`p`.`rate_id` AS `rate_id`,`p`.`creators` AS `creators`,`fr`.`name` AS `rate_name` from (`product` `p` left join `film_rate` `fr` on((`fr`.`rate_id` = `p`.`rate_id`)));