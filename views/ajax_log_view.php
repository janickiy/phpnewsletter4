<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

if(!empty($_REQUEST['offset']) && !empty($_REQUEST['number']) && !empty($_REQUEST['id_log']) && !empty($_REQUEST['strtmp'])){

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
	
	$data->logDetail();
}

