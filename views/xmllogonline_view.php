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

$arr = $data->getCurrentUserLog(10);

header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<document>\n";

if(is_array($arr)){
	foreach($arr as $row){
		echo "<emails>\n";
		echo "<id_user>".$row['id_user']."</id_user>\n";
		echo "<email>".$row['email']."</email>\n";
		echo "<status>".$row['success']."</status>\n";
		echo "<id_log>".$row['id_log']."</id_log>\n";
		echo "</emails>\n";
	} 
}

echo "</document>";

?>