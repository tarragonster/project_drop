CREATE TABLE `user_picks` (
  `pick_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `product_id` INT(11) NOT NULL ,
  `quote` TEXT NULL ,
  `created_at` INT(11) NOT NULL ,
  PRIMARY KEY (`pick_id`)
) ENGINE = InnoDB;
