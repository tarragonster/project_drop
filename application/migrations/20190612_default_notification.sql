ALTER TABLE `user_notification_setting` CHANGE `new_thumbs_up` `new_thumbs_up` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `user_notification_setting` CHANGE `comment_replies` `comment_replies` TINYINT(1) NOT NULL DEFAULT '1';

DELETE FROM `user_follow` WHERE user_id = follower_id;