ALTER TABLE `user` CHANGE `facebook_id` `facebook_id` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `user` CHANGE `fb_token` `fb_token` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
update user set facebook_id = NULL WHERE facebook_id = 'NULL';
update user set fb_token = NULL WHERE fb_token = 'NULL';