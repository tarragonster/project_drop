CREATE TABLE `user_notification_setting` (
 `user_id` int(11) NOT NULL,
 `follow_me` tinyint(1) NOT NULL DEFAULT '1',
 `mention_me` tinyint(1) NOT NULL DEFAULT '1',
 `like_comment` tinyint(1) NOT NULL DEFAULT '1',
 `reply_comment` tinyint(1) NOT NULL DEFAULT '1',
 PRIMARY KEY (`user_id`)
);