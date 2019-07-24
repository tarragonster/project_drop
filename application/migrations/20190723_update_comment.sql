ALTER TABLE `comments` ADD `edited_at` INT(11) NULL DEFAULT NULL AFTER `timestamp`;
ALTER TABLE `comment_replies` ADD `edited_at` INT(11) NULL DEFAULT NULL AFTER `timestamp`;
