<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_settings extends Model
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
	
	public function getSetting(){
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRow($result);	
	}

	public function getCharsetList(){
		$query = "SELECT * FROM ".$this->data->getTableName('charset')."";
		$result = $this->data->querySQL($query);	

		$temp = Array();
		while($row = $this->data->getRow($result))
		{
			$temp[$row['id_charset']] = charsetlist($row['charset']);
		}	
		
		return $temp;
	}
	
	public function updateSettings($fields){
		
		$result = $this->data->update($fields, $this->data->getTableName('settings'), '');
		
		if($result)
			return true;
		else
			return false;
	}	
}

?>