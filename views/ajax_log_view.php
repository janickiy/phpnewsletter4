<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

$arr = $data->getDetaillog($_REQUEST['offset'], $_REQUEST['number'], $_REQUEST['id_log'], $_REQUEST['strtmp']);

if(is_array($arr)){
	foreach($arr as $row){
		$catname = $row['id_cat'] == 0 ? $PNSL["lang"]["str"]["general"] : $row['catname'];
		$status = $row['success'] == 'yes' ? $PNSL["lang"]["str"]["send_status_yes"] : $PNSL["lang"]["str"]["send_status_no"]; 
		$read = $row['readmail'] == 'yes' ? $PNSL["lang"]["str"]["yes"] : $PNSL["lang"]["str"]["no"]; 

		echo "<tr>";
		echo "<td>".$row['name']."</td>";
		echo "<td>".$row['email']."</td>";
		echo "<td>".$catname."</td>";
		echo "<td>".$row['time']."</td>";
		echo "<td>".$status."</td>";
		echo "<td>".$read."</td>";
		echo "<td width=\"30%\">".$row['errormsg']."</td>";
		echo "</tr>";
	}
}

?>