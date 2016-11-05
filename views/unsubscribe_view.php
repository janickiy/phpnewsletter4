<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

if(empty($_GET['id'])) error($PNSL["lang"]["error"]["unsubscribe"]);
if(empty($_GET['token'])) error($PNSL["lang"]["error"]["unsubscribe"]);

$token = $data->getToken();

if($token == $_GET['token']){
	$result = $data->makeUnsubscribe();
	
	if(!$result) error($PNSL["lang"]["error"]["unsubscribe"]);
	
	echo '<!DOCTYPE html>';
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<title>".$PNSL["lang"]["str"]["title_unsubscribe"]."</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>".$PNSL["lang"]["msg"]["subscribe_removed"]."</center>\n";
	echo "</body>\n";
	echo "</html>";	
}
else error($PNSL["lang"]["error"]["unsubscribe"]);

?>