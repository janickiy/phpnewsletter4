<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

// require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."faq.tpl");

$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["faq"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["faq"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["faq"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

$tpl->assign('FAQ', $data->get_faq());

//footer
include_once "footer.php";

// display content
$tpl->display();

?>