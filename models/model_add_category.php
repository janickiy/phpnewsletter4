<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_add_category extends Model
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
	
	public function checkExistCatName($name)
	{
		$name = $this->data->escape($name);
		$query = "SELECT * FROM ".$this->data->getTableName('category')." WHERE name LIKE '".$name."'";	
		$result = $this->data->querySQL($query);
				
		if($this->data->getRecordCount($result) == 0)
			return false;
		else
			return true;
	}
	
	public function addNewCategory($fields)
	{
		return $this->data->insert($fields, $this->data->getTableName('category'));
	}	
}

?>