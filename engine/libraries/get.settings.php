<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

function get_settings($set)
{
	$db = new DBParser($set);
	
	$query = "SELECT * FROM ".$db->getTableName('settings')."";
	$result = $db->querySQL($query);
	$row = $db->getRow($result);

	return $row;
}

?>