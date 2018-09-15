DROP TABLE IF EXISTS  `collection`;

CREATE TABLE `collection` (
  `collection_id` int(11) NOT NULL,
  `collection_type` int(22) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `created` int(11) DEFAULT NULL,
  `short_bio` text,
  `status` tinyint(4) DEFAULT '1'
);

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`collection_id`, `collection_type`, `name`, `priority`, `created`, `short_bio`, `status`) VALUES
(1, 1, 'Trending', 3, NULL, 'Trending', 1),
(2, 1, 'Previews', 4, NULL, 'Previews', 1),
(3, 4, 'Top Picks', 5, NULL, 'Top Picks', 1),
(4, 3, 'Continue watching', 2, NULL, 'Continue watching', 1),
(5, 2, 'Slides', 1, NULL, 'Slides', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`collection_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;