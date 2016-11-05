ALTER TABLE `%prefix%settings` DROP `number_pos`;
ALTER TABLE `%prefix%settings` DROP `number_pos_users`;
ALTER TABLE `%prefix%settings` ADD `require_confirmation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `textconfirmation`; 