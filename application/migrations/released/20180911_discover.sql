CREATE TABLE `featured_profiles` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `status` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE`explore_previews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `product_id` INT(11) NOT NULL ,
  `status` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `featured_profiles` (`id`, `user_id`, `status`) VALUES (NULL, '70', '1'), (NULL, '71', '1'), (NULL, '72', '1');
INSERT INTO `explore_previews` (`id`, `product_id`, `status`) VALUES (NULL, '15', '1'), (NULL, '16', '1'), (NULL, '17', '1');
