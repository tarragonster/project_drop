CREATE TABLE `story_shared` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `story_id` INT(11) NOT NULL ,
  `friends` INT(11) NOT NULL ,
  `shared_at` INT(11) NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;


ALTER TABLE `story_shared`
  ADD CONSTRAINT `fk_story_shared_vs_users`
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `story_shared`
  ADD CONSTRAINT `fk_story_shared_vs_stories`
  FOREIGN KEY (`story_id`) REFERENCES `product`(`product_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;
