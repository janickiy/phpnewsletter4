<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

//require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."update.tpl");

$tpl->assign('STR_WARNING',$PNSL["lang"]["str"]["warning"]);
$tpl->assign('SCRIPT_VERSION',$PNSL["system"]["version"]);
$tpl->assign('INFO_ALERT',$PNSL["lang"]["info"]["update"]);

$tpl->assign('TITLE_PAGE',$PNSL["lang"]["title_page"]["update"]);
$tpl->assign('TITLE',$PNSL["lang"]["title"]["update"]);

//$tpl->assign('NAMESCRIPT',$PNSL["lang"]["script"]["name"]);

//menu
include_once "menu.php";

//form
$tpl->assign('PHP_SELF',$_SERVER['REQUEST_URI']);
$tpl->assign('STR_LICENSE_KEY',$PNSL["lang"]["str"]["license_key"]);
$tpl->assign('BUTTON_SAVE',$PNSL["lang"]["button"]["save"]);

//value
$tpl->assign('LICENSE_KEY',$data->getLicenseKey());

//footer
include_once "footer.php";

// display content
$tpl->display();

?>