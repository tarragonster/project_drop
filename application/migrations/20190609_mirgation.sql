update user_follow uf
left join user u on u.user_id = uf.follower_id
set timestamp = 0
WHERE u.email is null;

DELETE from user_follow WHERE timestamp = 0;
ALTER TABLE `user_follow`
  ADD  CONSTRAINT `fk_user_follow_vs_follower`
  FOREIGN KEY (`follower_id`)
  REFERENCES `user`(`user_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_notify` DROP INDEX `fk_notify_user1_idx`;
ALTER TABLE `user_notify` ADD CONSTRAINT `fk_user_notify_vs_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `notification_references` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `refer_type` ENUM('user','product','episode','comment') NOT NULL ,
  `notify_id` INT(11) NOT NULL ,
  `refer_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`), INDEX (`refer_id`)
) ENGINE = InnoDB;

ALTER TABLE `notification_references`
  ADD CONSTRAINT `fk_notification_references_vs_notifications`
  FOREIGN KEY (`notify_id`) REFERENCES `user_notify`(`notify_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

