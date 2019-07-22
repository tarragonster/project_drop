CREATE TABLE `user_reports` (
  `report_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `reporter_id` INT(11) NOT NULL ,
  `created_at` INT(11) NOT NULL ,
  PRIMARY KEY (`report_id`)
) ENGINE = InnoDB;

CREATE TABLE `comment_reports` (
  `report_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `comment_id` INT(11) NOT NULL ,
  `reporter_id` INT(11) NOT NULL ,
  `created_at` INT(11) NOT NULL ,
  PRIMARY KEY (`report_id`)
) ENGINE = InnoDB;
