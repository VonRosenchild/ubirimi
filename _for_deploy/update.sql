ALTER TABLE  `issue_work_log` CHANGE  `edited_flag`  `edited_flag` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `issue_attachment` CHANGE  `size`  `size` BIGINT( 20 ) UNSIGNED NULL ;
ALTER TABLE  `issue_attachment` CHANGE  `size`  `size` BIGINT( 20 ) UNSIGNED NULL DEFAULT NULL ;


====================== rulate pe live===========================

ALTER TABLE  `general_invoice` ADD  `email_sent_flag` TINYINT UNSIGNED NOT NULL DEFAULT  '0' AFTER  `number`;


ALTER TABLE  `general_invoice` ADD  `amount` BIGINT UNSIGNED NOT NULL AFTER  `client_id`;

ALTER TABLE  `general_invoice` CHANGE  `general_payment_id`  `client_id` BIGINT( 20 ) UNSIGNED NOT NULL;

ALTER TABLE  `client` ADD  `paymill_id` VARCHAR( 250 ) NULL AFTER  `sys_country_id`;

ALTER TABLE `client` ADD `vat_number` VARCHAR(50) NULL AFTER `district`;

ALTER TABLE  `cal_event_repeat` ADD  `end_after_occurrences` INT UNSIGNED NULL AFTER  `repeat_every`;

INSERT INTO  `yongo`.`cal_event_repeat_cycle` (
  `id` ,
  `name`
)
VALUES (
  '2',  'weekly'
);

drop TABLE general_payment;


ALTER TABLE `qn_tag` ADD `description` VARCHAR(255) NOT NULL AFTER `name`;
ALTER TABLE `qn_tag` ADD `date_updated` DATETIME NOT NULL AFTER `date_created`;




CREATE TABLE IF NOT EXISTS `qn_notebook` (
  `id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `default_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qn_notebook_note`
--

CREATE TABLE IF NOT EXISTS `qn_notebook_note` (
  `id` bigint(20) unsigned NOT NULL,
  `qn_notebook_id` bigint(20) unsigned NOT NULL,
  `summary` varchar(250) NOT NULL,
  `content` text,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qn_notebook_note_tag`
--

CREATE TABLE IF NOT EXISTS `qn_notebook_note_tag` (
  `id` bigint(20) unsigned NOT NULL,
  `qn_notebook_note_id` bigint(20) unsigned NOT NULL,
  `qn_tag_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qn_tag`
--

CREATE TABLE IF NOT EXISTS `qn_tag` (
  `id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `qn_notebook`
--
ALTER TABLE `qn_notebook`
ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `qn_notebook_note`
--
ALTER TABLE `qn_notebook_note`
ADD PRIMARY KEY (`id`), ADD KEY `qn_notebook_id` (`qn_notebook_id`);

--
-- Indexes for table `qn_notebook_note_tag`
--
ALTER TABLE `qn_notebook_note_tag`
ADD PRIMARY KEY (`id`), ADD KEY `qn_notebook_note_id` (`qn_notebook_note_id`,`qn_tag_id`);

--
-- Indexes for table `qn_tag`
--
ALTER TABLE `qn_tag`
ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `qn_notebook`
--
ALTER TABLE `qn_notebook`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `qn_notebook_note`
--
ALTER TABLE `qn_notebook_note`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `qn_notebook_note_tag`
--
ALTER TABLE `qn_notebook_note_tag`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `qn_tag`
--
ALTER TABLE `qn_tag`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `filter_favourite` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filter_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `filter_subscription` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filter_id` bigint(20) unsigned NOT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `period` varchar(200) NOT NULL,
  `email_when_empty_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

ALTER TABLE `client` ADD `is_payable` INT NOT NULL DEFAULT '1' AFTER `last_login`;

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


===============================


ALTER TABLE `project` CHANGE `help_desk_enabled_flag` `help_desk_enabled_flag` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `client` ADD UNIQUE(`company_domain`);

ALTER TABLE `help_sla_calendar_data` CHANGE `time_from` `time_from` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `help_sla_calendar_data` CHANGE `time_to` `time_to` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `issue_history` ADD `old_value_id` VARCHAR(250) NULL AFTER `new_value`, ADD `new_value_id` VARCHAR(250) NULL AFTER `old_value_id`;

ALTER TABLE  `workflow_step` CHANGE  `initial_step_flag`  `initial_step_flag` TINYINT( 3 ) UNSIGNED NULL DEFAULT  '0';

ALTER TABLE  `yongo_issue` CHANGE  `priority_id`  `priority_id` BIGINT( 20 ) UNSIGNED NULL;