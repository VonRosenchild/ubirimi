ALTER TABLE `project` CHANGE `service_desk_enabled_flag` `help_desk_enabled_flag` TINYINT(3) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `help_sla_calendar` CHANGE `client_id` `project_id` BIGINT(20) UNSIGNED NOT NULL;

ALTER TABLE `help_sla_goal` ADD `help_sla_calendar_id` BIGINT UNSIGNED NOT NULL AFTER `help_sla_id`, ADD INDEX (`help_sla_calendar_id`) ;

ALTER TABLE `yongo_issue_sla` ADD `value_between_cycles` INT NOT NULL DEFAULT '0' ;