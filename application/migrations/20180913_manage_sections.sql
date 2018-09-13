CREATE TABLE `user_profile_configs` (
  `user_id` INT(11) NOT NULL,
  `picks_enabled` INT(1) NOT NULL DEFAULT '1',
  `continue_enabled` INT(1) NOT NULL DEFAULT '1',
  `watch_enabled` INT(1) NOT NULL DEFAULT '1',
  `thumbs_up_enabled` INT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE = InnoDB;
