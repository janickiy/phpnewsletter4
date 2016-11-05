ALTER TABLE `%prefix%settings` DROP `number_pos`;
ALTER TABLE `%prefix%settings` DROP `number_pos_users`;
ALTER TABLE `%prefix%settings` ADD `require_confirmation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `textconfirmation`;
ALTER TABLE `%prefix%settings` ADD `theme` ENUM( 'default', 'dark' ) NOT NULL DEFAULT 'default' AFTER `language`; 
ALTER TABLE `%prefix%settings` ADD `random` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `sleep`;