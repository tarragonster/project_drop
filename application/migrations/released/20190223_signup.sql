CREATE TABLE `newsletter_signups` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NULL DEFAULT NULL ,
  `full_name` VARCHAR(255) NULL DEFAULT NULL ,
  `added_at` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;