delimiter $$

CREATE TABLE `quicktag_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_user_context` int(10) unsigned DEFAULT NULL,
  `tag_date_created` datetime NOT NULL,
  `tag_weight` double DEFAULT NULL,
  `tag_title` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `IDX_FF11E9291A46B076` (`tag_user_context`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci$$

