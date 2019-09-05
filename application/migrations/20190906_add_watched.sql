CREATE TABLE `user_watched_episodes` (
  `user_id` INT(11) NOT NULL ,
  `episode_id` INT(11) NOT NULL ,
  `updated_at` INT(11) NOT NULL ,
  PRIMARY KEY (`user_id`, `episode_id`)
) ENGINE = InnoDB;