<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_xmldaemonstat extends Model
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
	
	public function getMailingStatus()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('process')."";
		$result = $this->data->querySQL($query);
		$status = $this->data->getRow($result);	
		
		return $status['process'];		
	}	
}