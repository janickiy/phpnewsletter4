CREATE TABLE `%prefix%attach` (
  `id_attachment` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `id_template` int(7) NOT NULL,
  PRIMARY KEY (`id_attachment`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%aut` (
  `passw` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%category` (
  `id_cat` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%charset` (
  `id_charset` int(5) NOT NULL AUTO_INCREMENT,
  `charset` varchar(32) NOT NULL,
  PRIMARY KEY (`id_charset`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%licensekey` (
  `licensekey` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%log` (
  `id_log` int(7) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_log`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%process` (
  `process` enum('start','pause','stop') NOT NULL DEFAULT 'start'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%ready_send` (
  `id_ready_send` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(7) NOT NULL,
  `id_template` int(7) NOT NULL,
  `success` enum('yes','no') NOT NULL,
  `errormsg` text NOT NULL,
  `readmail` enum('yes','no') NOT NULL DEFAULT 'no',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_log` int(7) NOT NULL,
  PRIMARY KEY (`id_ready_send`),
  KEY `id_user` (`id_user`),
  KEY `id_send` (`id_template`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%settings` (
  `language` varchar(10) NOT NULL,
  `email` varchar(200) NOT NULL,
  `email_name` varchar(200) NOT NULL,
  `show_email` enum('no','yes') NOT NULL DEFAULT 'yes',
  `organization` varchar(200) NOT NULL,
  `smtp_host` varchar(200) NOT NULL,
  `smtp_username` varchar(200) NOT NULL,
  `smtp_password` varchar(200) NOT NULL,
  `smtp_port` int(8) NOT NULL DEFAULT '25',
  `smtp_aut` enum('no','plain','cram-md5') NOT NULL DEFAULT 'no',
  `smtp_secure` enum('no','ssl','tls') NOT NULL DEFAULT 'no',
  `smtp_timeout` int(6) NOT NULL,
  `how_to_send` tinyint(1) NOT NULL,
  `sendmail` varchar(150) NOT NULL,
  `id_charset` tinyint(4) NOT NULL DEFAULT '0',
  `content_type` tinyint(1) NOT NULL DEFAULT '1',
  `number_days` tinyint(4) NOT NULL DEFAULT '0',
  `make_limit_send` enum('yes','no') NOT NULL DEFAULT 'no',
  `re_send` enum('yes','no') NOT NULL DEFAULT 'no',
  `delete_subs` enum('yes','no') NOT NULL DEFAULT 'yes',
  `newsubscribernotify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `request_reply` enum('yes','no') NOT NULL DEFAULT 'no',
  `email_reply` varchar(200) NOT NULL,
  `show_unsubscribe_link` enum('yes','no') NOT NULL,
  `subjecttextconfirm` varchar(200) NOT NULL,
  `textconfirmation` text NOT NULL,
  `require_confirmation` enum('yes','no') NOT NULL DEFAULT 'no',
  `unsublink` text NOT NULL,
  `interval_type` enum('no','m','h','d') NOT NULL DEFAULT 'no',
  `interval_number` int(6) NOT NULL,
  `limit_number` int(6) NOT NULL,
  `precedence` enum('no','bulk','junk','list') NOT NULL DEFAULT 'bulk',
  `sleep` int(6) NOT NULL,
  `add_dkim` enum('no','yes') NOT NULL DEFAULT 'no',
  `dkim_domain` varchar(255) NOT NULL,
  `dkim_private` varchar(255) NOT NULL,
  `dkim_selector` varchar(255) NOT NULL,
  `dkim_passphrase` varchar(255) NOT NULL,
  `dkim_identity` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%subscription` (
  `id_sub` int(7) NOT NULL AUTO_INCREMENT,
  `id_user` int(7) NOT NULL,
  `id_cat` int(5) NOT NULL,
  PRIMARY KEY (`id_sub`),
  KEY `id_cat` (`id_cat`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%template` (
  `id_template` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `prior` enum('1','2','3') NOT NULL DEFAULT '3',
  `pos` int(7) NOT NULL,
  `id_cat` int(7) NOT NULL,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id_template`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%prefix%users` (
  `id_user` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `token` varchar(64) NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('active','noactive') NOT NULL DEFAULT 'noactive',
  `time_send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;