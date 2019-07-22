ALTER TABLE `user` ADD `user_type` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `level`;

DROP VIEW IF EXISTS `user_view`;
CREATE VIEW `user_view` as
select u.`user_id`, u.`user_name`, u.`email`, u.`birthday`, u.`bio`, u.`full_name`, u.`avatar`, u.`level`, u.user_type, u.`last_login`, u.`joined`, u.`status`, p.`name` AS `activity`, p.`product_id`
from `user` as u
left join `product` as p on p.`product_id` = u.`product_id`