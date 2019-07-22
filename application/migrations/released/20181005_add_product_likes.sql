CREATE TABLE `product_likes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `product_id` INT(11) NOT NULL ,
  `added_at` INT(11) NOT NULL ,
  PRIMARY KEY (`id`),
  INDEX (`user_id`), INDEX (`product_id`)
) ENGINE = InnoDB;
