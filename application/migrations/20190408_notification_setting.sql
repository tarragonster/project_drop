CREATE TABLE `user_notification_setting` (
  `user_id` INT(11) NOT NULL ,
  `new_followers` TINYINT(1) NOT NULL DEFAULT '1' ,
  `new_picks` TINYINT(1) NOT NULL DEFAULT '1' ,
  `new_watchlist` TINYINT(1) NOT NULL DEFAULT '1' ,
  `new_thumbs_up` TINYINT(1) NOT NULL DEFAULT '1' ,
  `trending` TINYINT(1) NOT NULL DEFAULT '1' ,
  `new_stories` TINYINT(1) NOT NULL DEFAULT '1' ,
  `product_updates` TINYINT(1) NOT NULL DEFAULT '1' ,
  `comment_mentions` TINYINT(1) NOT NULL DEFAULT '1' ,
  `comment_likes` TINYINT(1) NOT NULL DEFAULT '1' ,
  `comment_replies` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`user_id`)
) ENGINE = InnoDB;

ALTER TABLE `user_notification_setting`
  ADD CONSTRAINT `fk_notificationsettings_vs_user`
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `product_user_reviews` (
 `user_id` int(11) NOT NULL,
 `product_id` int(11) NOT NULL,
 `has_reviewed` tinyint(1) NOT NULL DEFAULT '0',
 `updated_at` INT(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`user_id`, `product_id`)
) ENGINE=InnoDB;

ALTER TABLE `product_user_reviews`
  ADD CONSTRAINT `fk_productuserreviews_vs_users`
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `product_user_reviews`
  ADD CONSTRAINT `fk_productuserreviews_vs_product`
  FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;
