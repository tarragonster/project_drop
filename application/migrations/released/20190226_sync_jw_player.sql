UPDATE `product` set `total_time` = '0' where `total_time` = '' or `total_time` is null;
ALTER TABLE `product` CHANGE `total_time` `total_time` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `jw_media_id`;
UPDATE `episode` set `total_time` = '0' where `total_time` = '' or `total_time` is null;
ALTER TABLE `episode` CHANGE `total_time` `total_time` DECIMAL(10,2) NOT NULL DEFAULT '0.0' AFTER `jw_media_id`;