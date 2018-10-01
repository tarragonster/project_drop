CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(6) NOT NULL DEFAULT '99999',
  `created_at` int(11) NOT NULL
);

INSERT INTO `questions` (`question_id`, `question`, `answer`, `priority`, `created_at`) VALUES
(NULL, 'Question 1', 'Answer 1', '1', UNIX_TIMESTAMP()),
(NULL, 'Question 2', 'Answer 2', '2', UNIX_TIMESTAMP()),
(NULL, 'Question 3', 'Answer 3', '3', UNIX_TIMESTAMP()),
(NULL, 'Question 4', 'Answer 4', '4', UNIX_TIMESTAMP());