CREATE TABLE `product_genres` (
  `product_id` INT(11) NOT NULL ,
  `genre_id` INT(11) NOT NULL ,
  `added_at` INT(11) NOT NULL ,
  PRIMARY KEY (`product_id`, `genre_id`)
) ENGINE = InnoDB;

ALTER TABLE `product_genres`
    ADD CONSTRAINT `fk_product_genres_vs_products`
    FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `product_genres`
    ADD CONSTRAINT `fk_product_genres_vs_genres`
    FOREIGN KEY (`genre_id`) REFERENCES `story_genres`(`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

