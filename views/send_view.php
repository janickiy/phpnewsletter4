<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

set_time_limit(0);

// authorization
Auth::authorization();

session_write_close();

$result = 0;

if($_REQUEST['activate']){
	if($data->updateProcess('start')) $result = $data->SendEmails(); 
} 

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<DOCUMENT>\n";
echo "<completed>yes</completed>\n";
echo "</DOCUMENT>";

?>