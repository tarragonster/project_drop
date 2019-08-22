CREATE TABLE `trending_search` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `times` int(11) NOT NULL,
 `updated_at` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `trending_search` (`keyword`, `times`, `updated_at`) VALUES
('sleep', 58, 1535363909),
('slee', 44, 1535363908),
('sate', 43, 1558325481),
('guava', 28, 1554893975),
('Apple', 26, 1534653241),
('guav', 21, 1541654039),
('Hybr', 16, 1544435156),
('Appl', 14, 1534653240),
('slep', 11, 1534215867),
('Hybrid', 11, 1544435167);