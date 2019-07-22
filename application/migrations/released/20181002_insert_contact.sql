ALTER TABLE `user` ADD `phone_number` VARCHAR(15) NULL DEFAULT NULL AFTER `email`, ADD INDEX (`phone_number`);

CREATE TABLE `contact_contacts` (
 `contact_id` int(11) NOT NULL AUTO_INCREMENT,
 `contact_type` smallint(6) NOT NULL DEFAULT '1',
 `contact` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
 `reference_id` int(11) NOT NULL DEFAULT '0',
 `created_at` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`contact_id`),
 KEY `contact` (`contact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `contact_contacts` ADD UNIQUE(`contact`);

CREATE TABLE `contact_friends` (
 `user_id` int(11) NOT NULL,
 `contact_id` int(11) NOT NULL,
 PRIMARY KEY (`user_id`,`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

