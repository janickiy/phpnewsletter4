<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<document>\n";
echo "<status>";

$result = $data->updateProcess($_GET['status']);

if($result)
	echo $_GET['status'];
else
	echo 'no';
	
echo "</status>\n";
echo "</document>";

?>