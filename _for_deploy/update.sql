ALTER TABLE `project` CHANGE `help_desk_enabled_flag` `help_desk_enabled_flag` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `client` ADD UNIQUE(`company_domain`);