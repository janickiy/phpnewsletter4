<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_process extends Model
{
	private $data;

	public function __construct() {
		$this->get_data();
	}

	public function get_data()
	{
		global $PNSL;
		$this->data = new DBParser($PNSL);
		
		return $this->data;
	}
	
	public function updateProcess($status)
	{
		if($status){
			$status = $this->data->escape($status);
			$query = "UPDATE ".$this->data->getTableName('process')." SET process='".$status."'";
			return $this->data->querySQL($query);
		}
		else return false;
	}	
}

?>