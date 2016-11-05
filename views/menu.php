<?php

$tpl->assign('ACTIVE_MENU',$_GET['task']);

$tpl->assign('MENU_TEMPLATES_TITLE',$PNSL["lang"]["menu"]["templates_title"]);
$tpl->assign('MENU_TEMPLATES',$PNSL["lang"]["menu"]["templates_name"]);
$tpl->assign('MENU_CREATE_NEW_TEMPLATE_TITLE',$PNSL["lang"]["menu"]["create_new_template_title"]);
$tpl->assign('MENU_CREATE_NEW_TEMPLATE',$PNSL["lang"]["menu"]["create_new_template_name"]);
$tpl->assign('MENU_SUBSCRIBERS_TITLE',$PNSL["lang"]["menu"]["subscribers_title"]);
$tpl->assign('MENU_SUBSCRIBERS',$PNSL["lang"]["menu"]["subscribers_name"]);
$tpl->assign('MENU_CATEGORY_TITLE',$PNSL["lang"]["menu"]["category_title"]);
$tpl->assign('MENU_CATEGORY',$PNSL["lang"]["menu"]["category_name"]);
$tpl->assign('MENU_SETTINGS_TITLE',$PNSL["lang"]["menu"]["settings_title"]);
$tpl->assign('MENU_SETTINGS',$PNSL["lang"]["menu"]["settings_name"]);
$tpl->assign('MENU_INTERFACE_SETTINGS_TITLE',$PNSL["lang"]["menu"]["interface_settings_title"]);
$tpl->assign('MENU_INTERFACE_SETTINGS',$PNSL["lang"]["menu"]["interface_settings"]);
$tpl->assign('MENU_SMTP_TITLE',$PNSL["lang"]["menu"]["SMTP_TITLE"]);
$tpl->assign('MENU_SMTP',$PNSL["lang"]["menu"]["smtp"]);
$tpl->assign('MENU_SECURITY_TITLE',$PNSL["lang"]["menu"]["security_title"]);
$tpl->assign('MENU_SECURITY',$PNSL["lang"]["menu"]["security"]);
$tpl->assign('MENU_LICENSE_KEY_TITLE',$PNSL["lang"]["menu"]["license_key_title"]);
$tpl->assign('MENU_LICENSE_KEY_TITLE',$PNSL["lang"]["menu"]["license_key"]);
$tpl->assign('MENU_IMPORT_TITLE',$PNSL["lang"]["menu"]["import_title"]);
$tpl->assign('MENU_IMPORT',$PNSL["lang"]["menu"]["import_name"]);
$tpl->assign('MENU_EXPORT_TITLE',$PNSL["lang"]["menu"]["export_title"]);
$tpl->assign('MENU_EXPORT',$PNSL["lang"]["menu"]["export_name"]);
$tpl->assign('MENU_LOG_TITLE',$PNSL["lang"]["menu"]["log_title"]);
$tpl->assign('MENU_LOG',$PNSL["lang"]["menu"]["log_name"]);
$tpl->assign('MENU_MAILING_OPTIONS_TITLE',$PNSL["lang"]["menu"]["mailing_options_title"]);
$tpl->assign('MENU_MAILING_OPTIONS',$PNSL["lang"]["menu"]["mailing_options"]);
$tpl->assign('MENU_UPDATE_TITLE',$PNSL["lang"]["menu"]["update_title"]);
$tpl->assign('MENU_UPDATE',$PNSL["lang"]["menu"]["update"]);

?>