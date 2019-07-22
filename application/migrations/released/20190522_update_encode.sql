update user_picks set quote = REPLACE(quote, '’', "'")
ALTER TABLE `user_picks` CHANGE `quote` `quote` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
