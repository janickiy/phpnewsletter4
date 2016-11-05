<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

function get_settings()
{
	global $PNSL;

	$db = new DBParser($PNSL);
	
	$query = "SELECT * FROM ".$db->getTableName('settings')."";
	$result = $db->querySQL($query);
	$row = $db->getRow($result);

	return $row;
}

?>