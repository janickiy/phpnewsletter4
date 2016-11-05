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

session_write_close();

$totalmails = 0;
$successmails = 0;
$unsuccessfulmails = 0;

$settings = $data->getSettings();

if($_GET['id_log']){
	$totalmails = $data->getTotalMails();
	$successmails = $data->getSuccessMails();
	$unsuccessfulmails = $data->getUnsuccessfulMails();
}

$sleep = $settings['sleep'] == 0 ? 0.5 : $settings['sleep'];
$timesec = intval(($totalmails-($successmails+$unsuccessfulmails))*$sleep);

$datetime = new DateTime();        
$datetime->setTime(0, 0, $timesec);     

header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<document>\n";
echo "<total>".$totalmails."</total>\n";
echo "<success>".$successmails."</success>\n";
echo "<unsuccessful>".$unsuccessfulmails."</unsuccessful>\n";
echo "<time>".$datetime->format('H:i:s')."</time>\n";
echo "</document>";

?>