UPDATE  `yongo_issue_sla` SET  `help_sla_goal_id` = NULL ,
  `started_flag` =0,
  `stopped_flag` =0,
  `started_date` = NULL ,
  `stopped_date` = NULL ,
  `value` = NULL;

ALTER TABLE  `cal_event_repeat` ADD  `on_day_0` TINYINT UNSIGNED NULL DEFAULT  '0' AFTER  `repeat_every` ,
ADD  `on_day_1` TINYINT UNSIGNED NULL DEFAULT  '0' AFTER  `on_day_0` ,
ADD  `on_day_2` TINYINT UNSIGNED NULL DEFAULT  '0' AFTER  `on_day_1` ,
ADD  `on_day_3` TINYINT UNSIGNED NULL DEFAULT  '0' AFTER  `on_day_2` ,
ADD  `on_day_4` TINYINT UNSIGNED NULL DEFAULT  '0' AFTER  `on_day_3` ,
ADD  `on_day_5` TINYINT UNSIGNED NULL DEFAULT  '0' AFTER  `on_day_4` ,
ADD  `on_day_6` TINYINT UNSIGNED NULL DEFAULT  '0' AFTER  `on_day_5`;


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