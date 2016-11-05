<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

$settings = $data->getSetting();

// require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."settings.tpl");

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_user"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);
$tpl->assign('STR_LOGOUT', $PNSL["lang"]["str"]["logout"]);

if($_POST["action"]){

	$fields = Array();

	$fields['language'] = trim($_POST['language']);
	$fields['theme'] = $_POST['theme'];	
	$fields['email'] = trim($_POST['email']);
	$fields['email_name'] = trim($_POST['email_name']);
	$fields['show_email'] = $_POST['show_email'] == 'on' ? "yes" : "no";
	$fields['organization'] = trim($_POST['organization']);
	$fields['smtp_host'] = trim($_POST['smtp_host']);
	$fields['smtp_username'] = trim($_POST['smtp_username']);
	$fields['smtp_password'] = trim($_POST['smtp_password']);
	$_POST['smtp_port'] = trim($_POST['smtp_port']);
	$fields['smtp_port'] = (int)$_POST['smtp_port'];
	$fields['smtp_aut'] = $_POST['smtp_aut'];
	$fields['smtp_secure'] = $_POST['smtp_secure'];
	$_POST['smtp_timeout'] = trim($_POST['smtp_timeout']);
	$fields['smtp_timeout'] = (int)$_POST['smtp_timeout'];
	$fields['how_to_send'] = (int)$_POST['how_to_send'];
	$fields['id_charset'] = (int)$_POST['id_charset'];
	$fields['content_type'] = (int)$_POST['content_type'];
	$fields['number_days'] = (int)$_POST['number_days'];
	$fields['make_limit_send'] = $_POST['make_limit_send'] == 'on' ? "yes" : "no";
	$fields['re_send'] = $_POST['re_send'] == 'on' ? "yes" : "no";
	$fields['random'] = $_POST['random'] == 'on' ? "yes" : "no";
	$fields['delete_subs'] = $_POST['delete_subs'] == 'on' ? "yes" : "no";
	$fields['newsubscribernotify'] = $_POST['newsubscribernotify'] == 'on' ? "yes" : "no";
	$fields['request_reply'] = $_POST['request_reply'] == 'on' ? "yes" : "no";
	$fields['show_unsubscribe_link'] = $_POST['show_unsubscribe_link'] == 'on' ? "yes" : "no";
	$fields['subjecttextconfirm'] = trim($_POST['subjecttextconfirm']);
	$fields['textconfirmation'] = trim($_POST['textconfirmation']);
	$fields['require_confirmation'] = $_POST['require_confirmation'] == 'on' ? "yes" : "no";
	$fields['unsublink'] = trim($_POST['unsublink']);
	$_POST['limit_number'] = trim($_POST['limit_number']);
	$_POST['limit_number'] = (int)$_POST['limit_number'];
	if($_POST['interval_type'] == '1') { $fields['interval_type'] = 'm'; }
	else if($_POST['interval_type'] == '2') { $fields['interval_type'] = 'h'; }
	else if($_POST['interval_type'] == '3') { $fields['interval_type'] = 'd'; }
	else { $fields['interval_type'] = 'no'; }
	$fields['interval_number'] = trim($_POST['interval_number']);
	$fields['limit_number'] = $_POST['limit_number']; 
	$fields['precedence'] = $_POST['precedence'];
	$fields['sendmail'] = trim($_POST['sendmail']);
	$fields["add_dkim"] = $_POST["add_dkim"] == 'on' ? "yes" : "no";
	$fields["dkim_domain"] = trim($_POST['dkim_domain']);	
	$fields["dkim_private"] = trim($_POST["dkim_private"]);
	$fields["dkim_selector"] = trim($_POST["dkim_selector"]);	
	$fields["dkim_passphrase"] = trim($_POST["dkim_passphrase"]);	
	$fields["dkim_identity"] = trim($_POST["dkim_identity"]);	
	$_POST['sleep'] = trim($_POST['sleep']);
	$fields["sleep"] = (int)$_POST['sleep'];	
	
	$result = $data->updateSettings($fields);

	if($result)
		$success = $PNSL["lang"]["msg"]["changes_added"];
	else
		$error = $PNSL["lang"]["error"]["web_apps_error"];
}

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["settings"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["settings"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["settings"]);

$settings = $data->getSetting();

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

//alert
if($error) {
	$tpl->assign('ERROR_ALERT', $error);
}
	
if(!empty($success)){ 
	$tpl->assign('MSG_ALERT', $success);
}

//value
$tpl->assign('OPTION_LANG', $settings['language']);
$tpl->assign('OPTION_THEME', $settings['theme']);
$tpl->assign('EMAIL', $settings['email']);
$tpl->assign('SHOW_EMAIL', $settings['show_email']);
$tpl->assign('SUBSCRIBER_NOTIFY', $settings['newsubscribernotify']);
$tpl->assign('SUBJECTTEXTCONFIRM', $settings['subjecttextconfirm']);
$tpl->assign('TEXTCONFIRMATION', $settings['textconfirmation']);
$tpl->assign('REQUIRE_CONFIRMATION', $settings['require_confirmation']);
$tpl->assign('UNSUBLINK', $settings['unsublink']);
$tpl->assign('SMTP_USERNAME', $settings['smtp_username']);
$tpl->assign('SMTP_PASSWORD', $settings['smtp_password']);
$tpl->assign('SMTP_PORT', $settings['smtp_port']);
$tpl->assign('REQUEST_REPLY', $settings['request_reply']);
$tpl->assign('INTERVAL_NUMBER', $settings['interval_number']);
$tpl->assign('INTERVAL_TYPE', $settings['interval_type']);
$tpl->assign('DELETE_SUBS', $settings['delete_subs']);
$tpl->assign('HOW_TO_SEND', $settings['how_to_send']);
$tpl->assign('SMTP_TIMEOUT', $settings['smtp_timeout']);
$tpl->assign('SMTP_SECURE', $settings['smtp_secure']);
$tpl->assign('SMTP_AUT', $settings['smtp_aut']);
$tpl->assign('SHOW_UNSUBSCRIBE_LINK', $settings['show_unsubscribe_link']);
$tpl->assign('RE_SEND', $settings['re_send']);
$tpl->assign('LIMIT_NUMBER', $settings['limit_number']);
$tpl->assign('MAKE_LIMIT_SEND', $settings['make_limit_send']);
$tpl->assign('NUMBER_DAYS', $settings['number_days']);
$tpl->assign('PRECEDENCE', $settings['precedence']);
$tpl->assign('SENDMAIL', $settings['sendmail']);
$tpl->assign('SLEEP', $settings['sleep']);
$tpl->assign('RANDOM', $settings['random']);
$tpl->assign('ADD_DKIM', $settings["add_dkim"]);
$tpl->assign('DKIM_DOMEN', $settings["dkim_domain"]);
$tpl->assign('DKIM_PRIVATE', $settings["dkim_private"]);
$tpl->assign('DKIM_SELECTOR', $settings["dkim_selector"]);
$tpl->assign('DKIM_PASSPHRASE', $settings["dkim_passphrase"]);
$tpl->assign('DKIM_IDENTITY', $settings["dkim_identity"]);

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('SET_LANGUAGE', $PNSL["lang"]["set"]["language"]);
$tpl->assign('SET_THEME',  $PNSL["lang"]["set"]["theme"]);
$tpl->assign('SET_OPTION_RU', $PNSL["lang"]["set"]["option_ru"]);
$tpl->assign('SET_OPTION_EN', $PNSL["lang"]["set"]["option_en"]);
$tpl->assign('SET_INTERFACE_SETTINGS', $PNSL["lang"]["set"]["interface_settings"]);
$tpl->assign('SET_EMAIL', $PNSL["lang"]["set"]["email"]);
$tpl->assign('SET_SHOW_EMAIL', $PNSL["lang"]["set"]["show_email"]);
$tpl->assign('SET_SUBSCRIBER_NOTIFY', $PNSL["lang"]["set"]["subscriber_notify"]);
$tpl->assign('SET_EMAIL_NAME', $PNSL["lang"]["set"]["email_name"]);

if($settings['email_name'] == '')
	$tpl->assign('EMAIL_NAME', $_SERVER['SERVER_NAME']);
else
	$tpl->assign('EMAIL_NAME', htmlspecialchars($settings['email_name']));

$tpl->assign('SET_ORGANIZATION', $PNSL["lang"]["set"]["organization"]);
$tpl->assign('ORGANIZATION', htmlspecialchars($settings['organization']));	
$tpl->assign('SET_SUBJECT_TEXTCONFIRM', $PNSL["lang"]["set"]["subject_textconfirm"]);
$tpl->assign('SET_TEXT_CONFIRMATION', $PNSL["lang"]["set"]["text_confirmation"]);
$tpl->assign('SET_REQUIRE_CONFIRMATION', $PNSL["lang"]["set"]["require_confirmation"]);
$tpl->assign('SET_UNSUBLINK', $PNSL["lang"]["set"]["unsublink"]);
$tpl->assign('SET_HINT', $PNSL["lang"]["set"]["hint"]);
$tpl->assign('SET_SMTP_SETTINGS', $PNSL["lang"]["set"]["smtp_settings"]);
$tpl->assign('SET_SMTP_HOST', $PNSL["lang"]["set"]["smtp_host"]);
$tpl->assign('SMTP_HOST', $settings['smtp_host']);
$tpl->assign('SET_SMTP_USERNAME', $PNSL["lang"]["set"]["username"]);
$tpl->assign('SET_SMTP_PASSWORD', $PNSL["lang"]["set"]["password"]);
$tpl->assign('SET_SMTP_PORT', $PNSL["lang"]["set"]["port"]);
$tpl->assign('SET_SMTP_TIMEOUT', $PNSL["lang"]["set"]["timeout"]);
$tpl->assign('SET_SMTP_SSL', $PNSL["lang"]["set"]["smtp_secure"]);
$tpl->assign('STR_NO', $PNSL["lang"]["str"]["no"]);
$tpl->assign('SMTP_SECURE_SSL', $PNSL["lang"]["str"]["smtp_secure_ssl"]);
$tpl->assign('SMTP_SECURE_TLS', $PNSL["lang"]["str"]["smtp_secure_tls"]);
$tpl->assign('SET_SMTP_AUT', $PNSL["lang"]["set"]["smtp_aut"]);
$tpl->assign('SET_SMTP_AUT_LOGIN', $PNSL["lang"]["set"]["smtp_aut_login"]);
$tpl->assign('SET_SMTP_AUT_PLAIN', $PNSL["lang"]["set"]["smtp_aut_plain"]);
$tpl->assign('SET_SMTP_AUT_CRAM', $PNSL["lang"]["set"]["smtp_aut_cram"]);
$tpl->assign('SET_SEND_PARAMETERS', $PNSL["lang"]["set"]["send_parameters"]);
$tpl->assign('SET_SHOW_UNSUBSCRIBE_LINK', $PNSL["lang"]["set"]["show_unsubscribe_link"]);
$tpl->assign('SET_REPLY', $PNSL["lang"]["set"]["request_reply"]);
$tpl->assign('SET_INTERVAL_TYPE', $PNSL["lang"]["set"]["interval_type"]);
$tpl->assign('SET_INTERVAL_TYPE_NO', $PNSL["lang"]["set"]["interval_type_no"]);
$tpl->assign('SET_INTERVAL_TYPE_M', $PNSL["lang"]["set"]["interval_type_m"]);
$tpl->assign('SET_INTERVAL_TYPE_H', $PNSL["lang"]["set"]["interval_type_h"]);
$tpl->assign('SET_INTERVAL_TYPE_D', $PNSL["lang"]["set"]["interval_type_d"]);
$tpl->assign('SET_RE_SEND', $PNSL["lang"]["set"]["re_send"]);
$tpl->assign('SET_NUMBER_LIMIT', $PNSL["lang"]["set"]["number_limit"]);
$tpl->assign('SET_NUMBER_DAYS', $PNSL["lang"]["set"]["number_days"]);
$tpl->assign('SET_IPRECEDENCE_NO', $PNSL["lang"]["str"]["no"]);
$tpl->assign('SET_CHARSET', $PNSL["lang"]["set"]["charset"]);
$tpl->assign('SET_HOW_TO_SEND', $PNSL["lang"]["set"]["how_to_send"]);
$tpl->assign('SET_HOW_TO_SEND_OPTION_1', $PNSL["lang"]["set"]["how_to_send_option_1"]);
$tpl->assign('SET_HOW_TO_SEND_OPTION_2', $PNSL["lang"]["set"]["how_to_send_option_2"]);
$tpl->assign('SET_HOW_TO_SEND_OPTION_3', $PNSL["lang"]["set"]["how_to_send_option_3"]);
$tpl->assign('SET_SENDMAIL_PATH', $PNSL["lang"]["set"]["sendmail"]);
$tpl->assign('SET_SLEEP', $PNSL["lang"]["set"]["sleep"]);
$tpl->assign('SET_RANDOM', $PNSL["lang"]["set"]["random"]);
$tpl->assign('SET_ADD_DKIM', $PNSL["lang"]["set"]["add_dkim"]);
$tpl->assign('SET_DKIM_DOMEN', $PNSL["lang"]["set"]["dkim_domen"]);
$tpl->assign('SET_DKIM_PRIVATE', $PNSL["lang"]["set"]["dkim_private"]);
$tpl->assign('SET_DKIM_SELECTOR', $PNSL["lang"]["set"]["dkim_selector"]);
$tpl->assign('SET_DKIM_PASSPHRASE', $PNSL["lang"]["set"]["dkim_passphrase"]);
$tpl->assign('SET_DKIM_IDENTITY', $PNSL["lang"]["set"]["dkim_identity"]);
$tpl->assign('BUTTON_APPLY', $PNSL["lang"]["button"]["apply"]);
$tpl->assign('BUTTON_BY_DEFAULT', $PNSL["lang"]["button"]["by_default"]);

$temp = $data->getCharsetList();

asort($temp);

$option = '';
foreach($temp as $key => $value){
	$selected = ($key == $settings['id_charset'] ? ' selected="selected"' : "");
	$option .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
}

$tpl->assign('OPTION', $option);
$tpl->assign('SET_CONTENT_TYPE', $PNSL["lang"]["set"]["content_type"]);
$tpl->assign('CONTENT_TYPE', $settings['content_type']);

// footer
include_once "footer.php";

// display content
$tpl->display();
