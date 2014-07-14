!!! adauga calendarul default la fiecare proiect de help desk si modifica fiecare goal sa aibe acel calendar default

ALTER TABLE `project` CHANGE `service_desk_enabled_flag` `help_desk_enabled_flag` TINYINT(3) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `help_sla_calendar` CHANGE `client_id` `project_id` BIGINT(20) UNSIGNED NOT NULL;

ALTER TABLE `help_sla_goal` ADD `help_sla_calendar_id` BIGINT UNSIGNED NOT NULL AFTER `help_sla_id`, ADD INDEX (`help_sla_calendar_id`) ;

ALTER TABLE `yongo_issue_sla` ADD `value_between_cycles` INT NOT NULL DEFAULT '0' ;

ALTER TABLE `help_sla_calendar_data` ADD `not_working_flag` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `time_to`;

ALTER TABLE `help_sla_calendar` ADD `default_flag` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `sys_timezone_id`;

ALTER TABLE `notification_scheme_data` ADD `all_watchers` TINYINT UNSIGNED NULL AFTER `component_lead`;

ALTER TABLE `project` CHANGE `service_desk_enabled_flag` `help_desk_enabled_flag` TINYINT(3) UNSIGNED NULL DEFAULT NULL;

CREATE TABLE `field_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` bigint(20) unsigned NOT NULL,
  `value` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `help_sla_calendar` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `sys_timezone_id` bigint(20) unsigned NOT NULL,
  `default_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_timezone_id` (`sys_timezone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE `help_sla_calendar_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `help_sla_calendar_id` bigint(20) unsigned NOT NULL,
  `day_number` int(10) unsigned NOT NULL,
  `time_from` varchar(8) NOT NULL,
  `time_to` varchar(8) NOT NULL,
  `not_working_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `help_calendar_id` (`help_sla_calendar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO `yongo`.`sys_field_type` (`id`, `name`, `description`, `code`) VALUES ('6', 'Select List (Single Choice)', 'A single select list with a configurable list of options', 'select_list_single');

ALTER TABLE `field_data` ADD `date_updated` DATETIME NULL ;

ALTER TABLE `client` ADD `last_login` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `user` ADD `last_login` DATETIME NULL DEFAULT NULL ;