ALTER TABLE `yongo_issue_sla` DROP `value_between_cycles`;
ALTER TABLE  `help_sla_calendar` CHANGE  `sys_timezone_id`  `sys_timezone_id` BIGINT( 20 ) UNSIGNED NULL;

====================== rulate pe live===========================

ALTER TABLE `project` CHANGE `help_desk_enabled_flag` `help_desk_enabled_flag` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `client` ADD UNIQUE(`company_domain`);

ALTER TABLE `help_sla_calendar_data` CHANGE `time_from` `time_from` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `help_sla_calendar_data` CHANGE `time_to` `time_to` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `issue_history` ADD `old_value_id` VARCHAR(250) NULL AFTER `new_value`, ADD `new_value_id` VARCHAR(250) NULL AFTER `old_value_id`;

ALTER TABLE  `workflow_step` CHANGE  `initial_step_flag`  `initial_step_flag` TINYINT( 3 ) UNSIGNED NULL DEFAULT  '0';

ALTER TABLE  `yongo_issue` CHANGE  `priority_id`  `priority_id` BIGINT( 20 ) UNSIGNED NULL;