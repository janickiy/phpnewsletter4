<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

$update = new Update();

if($update->checkNewVersion($PNSL["system"]["version"])){
	$PNSL["lang"]["str"]["update_warning"] = str_replace('%SCRIPTNAME%', $PNSL["lang"]["script"]["name"], $PNSL["lang"]["str"]["update_warning"]);
	$PNSL["lang"]["str"]["update_warning"] = str_replace('%VERSION%', $update->getVersion(), $PNSL["lang"]["str"]["update_warning"]);
	$PNSL["lang"]["str"]["update_warning"] = str_replace('%CREATED%', $update->getCreated(), $PNSL["lang"]["str"]["update_warning"]);
	$PNSL["lang"]["str"]["update_warning"] = str_replace('%DOWNLOADLINK%', $update->getDownloadLink(), $PNSL["lang"]["str"]["update_warning"]);
	
	header('Content-Type: application/xml; charset=utf-8');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<DOCUMENT>\n";
	echo "<warning>".htmlspecialchars($PNSL["lang"]["str"]["update_warning"])."</warning>\n";
	echo "</DOCUMENT>";	
}

?>