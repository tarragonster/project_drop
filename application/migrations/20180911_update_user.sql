ALTER TABLE `user` ADD `birthday` VARCHAR(10) NULL DEFAULT NULL AFTER `level`;
ALTER TABLE `user` ADD `bio` TEXT NULL AFTER `full_name`;

DROP VIEW IF EXISTS `user_view`;
CREATE VIEW `user_view` as select `user`.`user_id` AS `user_id`,`user`.`user_name` AS `user_name`,`user`.`email` AS `email`, `user`.`birthday` AS `birthday`, `user`.`bio` AS `bio`,`user`.`full_name` AS `full_name`,`user`.`avatar` AS `avatar`,`user`.`level` AS `level`,`user`.`last_login` AS `last_login`,`user`.`joined` AS `joined`,`user`.`status` AS `status`,`product`.`name` AS `activity`,`product`.`product_id` AS `product_id` from (`user` left join `product` on((`product`.`product_id` = `user`.`product_id`)));