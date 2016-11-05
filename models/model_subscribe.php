<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_subscribe extends Model
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
	
	public function getToken()
	{
		$_GET['id'] = $this->data->escape($_GET['id']);
		
		$query = "SELECT token FROM ".$this->data->getTableName('users')." WHERE id_user=".$_GET['id'];
		$result = $this->data->querySQL($query);
		$row = $this->data->getRow($result);
		
		return $row['token'];	
	}
	
	public function makeActivateSub()
	{
		$_GET['id'] = $this->data->escape($_GET['id']);
		
		$query = "UPDATE ".$this->data->getTableName('users')." set status='active' WHERE id_user=".$_GET['id'];
		
		return $this->data->querySQL($query);	
	}
}

?>