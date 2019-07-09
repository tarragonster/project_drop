UPDATE `collection` SET `name` = "Friend's Top Picks" WHERE `collection_id` = 3;
UPDATE `collection` SET `short_bio` = "Friend's Top Picks" WHERE `collection_id` = 3;

ALTER TABLE `collection` CHANGE `collection_type` `collection_type` ENUM('1','2','3','4','5','6','7') NOT NULL DEFAULT '1';

INSERT INTO `collection` (`collection_id`, `collection_type`, `limit`, `name`, `priority`, `created`, `short_bio`, `status`) VALUES
(NULL, '6', '25', 'Suggested Users', '6', UNIX_TIMESTAMP(), 'Suggested Users', '1'),
(NULL, '7', '25', 'Friends Watching', '7', UNIX_TIMESTAMP(), 'Friends Watching', '1');

CREATE TABLE `story_genres` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `product` ADD `genre_id` INT(11) NULL AFTER `category_id`;
INSERT INTO `story_genres` (`id`, `name`) VALUES
(NULL, 'Action'),
(NULL, 'Drama'),
(NULL, 'Comedy'),
(NULL, 'Adventure'),
(NULL, 'Musicals'),
(NULL, 'Horror');

ALTER TABLE `story_genres` ADD `priority` SMALLINT(6) NOT NULL DEFAULT '1000' AFTER `name`, ADD `status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `priority`;

ALTER VIEW `product_view` AS select `p`.`product_id` AS `product_id`,`p`.`genre_id` AS `genre_id`,`p`.`image` AS `image`,`p`.`background_img` AS `background_img`,`p`.`name` AS `name`,`p`.`total_time` AS `total_time`,`p`.`publish_year` AS `publish_year`,`p`.`short_bio` AS `short_bio`,`p`.`description` AS `description`,`p`.`trailler_url` AS `trailler_url`,`p`.`trailler_image` AS `trailler_image`,`p`.`jw_media_id` AS `jw_media_id`,`p`.`paywall_episode` AS `paywall_episode`,`p`.`priority` AS `priority`,`p`.`status` AS `status`,`p`.`rate_id` AS `rate_id`,`p`.`creators` AS `creators`,`fr`.`name` AS `rate_name` from (`product` `p` left join `film_rate` `fr` on((`fr`.`rate_id` = `p`.`rate_id`)))
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_vs_genres`
  FOREIGN KEY (`genre_id`) REFERENCES `story_genres`(`id`)
  ON DELETE SET NULL ON UPDATE SET NULL;

ALTER TABLE `story_genres` ADD `image` VARCHAR(255) NOT NULL AFTER `name`;