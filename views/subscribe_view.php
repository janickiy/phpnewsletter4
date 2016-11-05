<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

if(empty($_GET['id'])) error($PNSL["lang"]["error"]["activate_subscription"]);
if(empty($_GET['token'])) error($PNSL["lang"]["error"]["activate_subscription"]);

$token = $data->getToken();

if($token == $_GET['token']){
	$result = $data->makeActivateSub();
	
	if($result){
		echo '<!DOCTYPE html>';
		echo "<html>\n";
		echo "<head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
		echo "<title>".$PNSL["lang"]["str"]["title_activate_sub"]."</title>\n";
		echo "</head>\n";
		echo "<body>\n";
		echo "<center>".$PNSL["lang"]["msg"]["successful_activation"]."</center>\n";
		echo "</body>\n";
		echo "</html>";
	}
	else error($PNSL["lang"]["error"]["activate_subscription"]);
}
else error($PNSL["lang"]["error"]["activate_subscription"]);

?>