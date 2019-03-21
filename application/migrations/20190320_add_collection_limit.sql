ALTER TABLE `collection` ADD `limit` SMALLINT(6) NOT NULL DEFAULT '10' AFTER `collection_type`;
UPDATE `collection` SET `limit` = '25' WHERE `collection`.`collection_id` = 5;
