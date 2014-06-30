ALTER TABLE  `user` ADD  `country_id` BIGINT UNSIGNED NULL AFTER  `client_id` ;

ALTER TABLE  `yongo_issue` ADD  `user_reported_ip` VARCHAR( 30 ) NULL AFTER  `environment` ;

ALTER TABLE `yongo_issue` CHANGE `user_reported_ip` `user_reported_ip` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;