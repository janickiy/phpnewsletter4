<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

function getCurrentMailingStatus()
{
	global $PNSL;
		
	$db = new DBParser($PNSL);
		
	$query = "SELECT * FROM ".$db->getTableName('process')."";
	$result = $db->querySQL($query);
	$row = $db->getRow($result);

	return $row['process'];
}

?>