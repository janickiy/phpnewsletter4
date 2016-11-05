<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// set error reporting level
Error_Reporting(0);
define("DEBUG", 1);

$PNSL = array();

// main directories
$PNSL["system"]["dir_root"]   = dirname(__FILE__)."/";
$PNSL["system"]["dir_attach"] = "attach/";//attachment
$PNSL["system"]["dir_config"] = "config/";// Config
$PNSL["system"]["dir_controllers"] = "controllers/";//controllers
$PNSL["system"]["dir_engine"] = "engine/";// Engine
$PNSL["system"]["dir_templates"] = "templates/";//templates
$PNSL["system"]["dir_libs"] = "libraries/";//libraries
$PNSL["system"]["dir_controllers"] = "controllers/";
$PNSL["system"]["dir_models"] = "models/";
$PNSL["system"]["dir_views"] = "views/";
$PNSL["system"]["dir_classes"] = "classes/";
$PNSL["path"] = str_replace("//","/", "/" .trim(str_replace(chr(92),"/", substr($PNSL["system"]["dir_root"],strlen($_SERVER["DOCUMENT_ROOT"]))),"/")."/");
													
//Script version
$PNSL["system"]["version"] = "4.0.16";

//require libs
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"].$PNSL["system"]["dir_libs"]."functions.php";

//require engine
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"]."model.php";
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"]."view.php";
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"]."controller.php";

//require congigs
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_config"]."config.php";

//equire template
$PNSL["system"]["template"] =  $PNSL["system"]["dir_root"].$PNSL["system"]["dir_templates"]."themes/default/";

//require db classes
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"].$PNSL["system"]["dir_classes"]."class.db.php";
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"].$PNSL["system"]["dir_classes"]."class.exception_mysql.php";

//get settings
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"].$PNSL["system"]["dir_libs"]."get.settings.php";
$settings = get_settings($PNSL);

if($settings["language"])
	$lang_file = $PNSL["system"]["dir_root"].$PNSL["system"]["dir_config"]."language/".$settings["language"].".php";
else 
	$lang_file = $PNSL["system"]["dir_root"].$PNSL["system"]["dir_config"]."language/en.php";

if(file_exists($lang_file))
	include $lang_file;
else
	exit('ERROR: Language file can not load!');

require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"].$PNSL["system"]["dir_classes"]."class.authorization.php";
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_engine"].$PNSL["system"]["dir_classes"]."class.update.php";

// check install
if(is_file($PNSL["system"]["dir_root"].$PNSL["system"]["dir_config"]."/config.php") && is_dir($PNSL["system"]["dir_root"]."install")){
	header("Content-type: text/html; charset=utf-8");
    
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>PHP Newsletter ".$PNSL["system"]["version"]."</title>\n";
	echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
	echo "<style type=\"text/css\">\n";
	echo 'p, li {font-family: Arial; font-size: 11px;color: black;}';
	echo "</style>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo '<p><a target="_blank" href="'.$PNSL["lang"]["str"]["url_info"].'">PHP Newsletter</a> | ';
	echo "<p>".str_replace('%URL%',$PNSL["path"].'install/',$PNSL["lang"]["str"]["install_msg"])."</p>\n";
	echo "</body>\n";
	echo '</html>';
    
	exit;
}

//require router
require_once $PNSL["system"]["dir_engine"]."route.php";

//run router
Route::start();

?>